define(
[
    './Selector',
    './Grid',
    './Utils',
    './EventBus',
    'EventEmitter'
],
// +---------------+------------+
// | Signal        | arguments  |
// +---------------+------------+
// | word.found    | word       |
// | board.resize  | size       |
// | board.created | words used |
// +---------------+------------+
function(Selector, Grid, $, eventBus, UI) {
    'use strict';

    var defaultOptions = {
        alphabet: 'abcdefghijklmnopqrstuvwxyz',
        fontSize: 14,
        showSolveButton: true,
        fontFamily: 'Arial',
        color: 'black',
        selectColor: 'blue',
        size: 20,
        totalWords: 20,
        wordDirections: ['horizontal', 'vertical', 'diagonal', 'reverse']
    },

    abs = Math.abs,

    totalWords,
    gameFinish;

    // clear canvas
    function clear(ctx) {
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height)
    }

    // get 2d context and initialize excanvas if ie < 9
    function getContext(id) {
        var cv = $.$(id);
        if (!cv.getContext && window.G_vmlCanvasManager) {
            G_vmlCanvasManager.initElement(cv);
        }

        return cv.getContext('2d');
    }

    // Resize a canvas
    function resizeCanvas(canvas) {
        canvas.width = canvas.height = this.boardSize;
    }

    /**
     * Board constructor
     * @param {object} options see defaultOptions
     */
    function Board(options) {
        this.setOptions(options);
        this.words   = this.options.words;
        this.founds  = [];

        this.channel = eventBus.channel(this.options.uid);

        this.boardCtx = getContext('html5-wordsearch-grid-'+options.uid);
        this.linesCtx = getContext('html5-wordsearch-lines-'+options.uid);
        this.selectorCtx = getContext('html5-wordsearch-layer-'+options.uid);

        this.canvases = [this.boardCtx.canvas,
                         this.linesCtx.canvas,
                         this.selectorCtx.canvas];

        this.selector = new Selector(this.selectorCtx.canvas);
        this.selector.on('start', _.bind(higlightSelection, this));
        this.selector.on('clear', _.bind(clear, this, this.selectorCtx));
        this.selector.on('move', _.bind(drawSelection, this));
        this.selector.on('stop', _.bind(clear, this, this.selectorCtx));
        this.selector.on('stop', _.bind(checkWord, this));

        this.channel.on('time.finish', _.bind(this.stop, this));
        this.channel.on('word.found', _.bind(drawAllFounds, this));
        this.channel.on('word.hint', _.bind(drawAllFounds, this));
        this.channel.on('word.request.hint', _.bind(wordHint, this));
        this.channel.on('options.change', _.bind(this.setOptions, this));
    }

    EventEmitter.mixin(Board);

    var proto = Board.prototype;

    proto.setOptions = function(options) {
        this.options = _.defaults(options || {}, defaultOptions);
    };

    proto.restart = function() {
        this.calcSizes();
        this.founds.length = 0;

        this.data = Grid.create({
            alphabet: this.options.alphabet,
            size: this.options.size,
            words: _.keys(this.words),
            directions: this.options.wordDirections,
            totalWords: this.options.totalWords
        });

        // map positions to words in list
        this.hints = _.object(_.map(this.data.used, function(value, key){
            return [this.words[key], value];
        }, this));

        var used = _.pluck(this.data.used, 'word');

        // used to know when the game finish
        this._totalWords_ = used.length;
        this._gameFinish_ = false;

        // Let know that the board was created and which words where used
        this.channel.emit('board.created', {
            grid: this.data.grid,
            words: _.values(_.pick(this.words, used)),
            solutions: this.hints
        });

        this.draw();
    };

    proto.start = proto.restart;
    
    proto.stop = function() {
        this._gameFinish_ = true;
        this.channel.emit('game.finish');
    };

    // resize all canvas and the container
    proto.calcSizes = function() {
        this.cellSize  = this.options.fontSize * 2;
        this.boardSize = this.cellSize * this.options.size;

        this.selector.setCellSize(this.cellSize);
        _.each(this.canvases, resizeCanvas, this);

        this.channel.emit('board.resize', this.boardSize);
    };

    proto.redraw = function() {
        this.calcSizes();
        this.draw();
        drawAllFounds.call(this);
    };

    proto.draw = function() {
        var grid      = this.data.grid,
            cellSize  = this.cellSize,
            fontSize  = this.options.fontSize,
            offsetX   = fontSize,
            offsetY   = parseInt(fontSize * 1.5, 10);

        this.boardCtx.fillStyle = this.options.color;
        this.boardCtx.font = fontSize + 'px ' + this.options.fontFamily + ', Arial';
        this.boardCtx.textAlign = 'center';

        for (var i = 0; i < this.options.size; i++)
        {
            for (var j = 0; j < this.options.size; j++)
            {
                this.boardCtx.fillText(grid[i][j], 
                                       cellSize * j + offsetX,
                                       cellSize * i + offsetY);
            }
        }
    };

    proto.setWords = function(words) {
        this.words = prepareWords.call(this, words);
        this.restart();
    };

    // remove characters not in alphabet
    function prepareWords(words) {
        var notInAlphabet = /[\s']+/ig;

        return _.object(_.map(words, function(value, key){
            return [key.toLowerCase().replace(notInAlphabet, ''), value];
        }));
    }

    function setLineStyle(ctx, color) {
        ctx.lineCap = 'round';
        ctx.strokeStyle = color || this.options.selectColor;
        ctx.globalAlpha = 0.5;
        ctx.lineWidth = this.cellSize * 0.8;
    }

    function higlightSelection(selection) {
        var cellSize = this.cellSize,
            halfCell = ~~(cellSize / 2);
            
        this.selectorCtx.beginPath();
        setLineStyle.call(this, this.selectorCtx);
        this.selectorCtx.lineWidth = ~~(cellSize/4);
        this.selectorCtx.arc(selection.col * cellSize + halfCell,
                          selection.row * cellSize + halfCell,
                          halfCell, 0, Math.PI * 2, true);
        this.selectorCtx.closePath();
        this.selectorCtx.stroke();
    }
    
    function drawSelection(start, end) {
        clear(this.selectorCtx);
        setLineStyle.call(this, this.selectorCtx);
        
        var cellSize = this.cellSize,
            halfCell = ~~(cellSize / 2),
            ctx = this.selectorCtx;
        ctx.beginPath();
        ctx.moveTo(start.col * cellSize + halfCell, start.row * cellSize + halfCell);
        ctx.lineTo(end.x, end.y);
        ctx.stroke();
        ctx.closePath();
    }

    function drawLine(ctx, start, end) {
        var cellSize = this.cellSize,
            halfCell = ~~(cellSize / 2);
        ctx.moveTo(start.col * cellSize + halfCell, start.row * cellSize + halfCell);
        ctx.lineTo(end.col * cellSize + halfCell, end.row * cellSize + halfCell);
    }
    
    function drawAllFounds() {
        var i, length = this.founds.length;

        clear(this.linesCtx);
        setLineStyle.call(this, this.linesCtx, 'red');
        
        this.linesCtx.beginPath();
        this.linesCtx.moveTo(-100, -100);
        this.linesCtx.lineTo(-110, -110);
        this.linesCtx.stroke();
        for (i = 0; i < length; i+=2) {
            drawLine.call(this, this.linesCtx, this.founds[i], this.founds[i+1]);
        }
        this.linesCtx.stroke();
        this.linesCtx.closePath();
    }

    function checkFinish() {
        if (!this._gameFinish_ && this._totalWords_ === 0)
        {
            this.stop();
        }
    }

    function addWord(word, start, end) {
        if (!this.data.used[word])
            return false;
        
        this._totalWords_--;
        this.founds.push(start, end);
        setTimeout(_.bind(checkFinish, this));
        delete this.data.used[word];
        return true;
    } 
    
    function wordHint(word) {
        if (this._gameFinish_ || !this.options.showSolveButton)
            return;
        
        var hint = this.hints[word];

        if (addWord.call(this, hint.word, hint.start, hint.end))
            this.channel.emit('word.hint', word);
    }

    function checkWord(start, end) {
        if (this._gameFinish_) return;
        
        var characters = collectCharacters(this.data.grid, start, end);
        var word1 = characters.join('').toLowerCase(),
            word2 = characters.reverse().join('').toLowerCase(),
            word  = this.data.used[word1] ? word1 : word2;

        if (addWord.call(this, word, start, end))
            this.channel.emit('word.found', this.words[word].toLowerCase());
    }

    function collectCharacters(grid, start, end) {
        var isValidEndCol = end.col > -1 && end.col < grid.length,
            isValidEndRow = end.row > -1 && end.row < grid.length,
            isDiagonal   = abs(start.col-end.col) === abs(start.row-end.row),
            isHorizontal = start.row == end.row,
            isVertical   = start.col == end.col;

        if (!(isValidEndCol && isValidEndRow) || (!isDiagonal && !isHorizontal && !isVertical))
            return [];

        var deltax = end.col - start.col,
            deltay = end.row - start.row,
            // in which direction we should advance
            vx = deltax > 0 ? 1 : deltax < 0 ? -1 : 0,
            vy = deltay > 0 ? 1 : deltay < 0 ? -1 : 0,
            x = start.col,
            y = start.row,
            // include last  col/row
            endx = end.col + vx,
            endy = end.row + vy,
            characters = [];

        while (x !== endx || y !== endy) {
            characters.push(grid[y][x]);

            // move just if needed
            x += x !== endx ? vx : 0;
            y += y !== endy ? vy : 0;
        }

        return characters;
    }

    return Board;
});
