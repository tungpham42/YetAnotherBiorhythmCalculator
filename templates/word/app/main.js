requirejs.config({
    urlArgs: "bust=" + (new Date).getTime(),

    packages: ['ModalWindow'],
    map: {
        "*": {
            'EventEmitter': 'vendor/EventEmitter.min',
            'underscore': 'vendor/underscore',
            'json': 'vendor/json2',
            'i18n': 'vendor/require-plugins/i18n',
            'text': 'vendor/require-plugins/text',
            'css':  'vendor/require-plugins/css',
            'css-builder': 'vendor/require-plugins/css-builder', 
            'normalize': 'vendor/require-plugins/normalize'
        }
    },

    pragmas: {
        excludeDownload: true
    },

    skipDirOptimize: true,

    dir: "../build",
    name: "main",
    include: ["EventEmitter", "./modules/EventBus"],
    //inlineText: false,

    shim: {
        json: {
            exports: 'JSON'
        },

        'underscore': {
            exports: '_'
        }
    }
});

define(
[
    './modules/CustomUI',
    './modules/Board',
    './modules/Score',
    './modules/Description',
    './modules/EventBus',
    './modules/Utils',
    //>>excludeStart("excludeDownload", pragmas.excludeDownload);
    './modules/Download',
    //>>excludeEnd("excludeDownload");
    './modules/Data'
],

function(UI, Board, Timer, Description, eventBus, $, Downloader) {

    var points = {
        ON_FOUND: 10,
        ON_HINT: -10
    },

    defaultOptions = {
        timer: 'html5-wordsearch-timer',
        score: 'html5-wordsearch-score',
        url: 'words/default.txt',
        allowDownload: false,
        showDescriptions: true
    };

    function Soup(options) {
        this.options = options = _.defaults(options||{}, defaultOptions);
        options.uid = (new Date).getTime();
        this.channel = eventBus.channel(options.uid);
        this.ui    = new UI(options);
        this.board = new Board(options);
        this.timer = new Timer(options);
        this.description = new Description(options);

        //>>excludeStart("excludeDownload", pragmas.excludeDownload);
        if (this.options.allowDownload)
            this.downloader = new Downloader(this.options);
        //>>excludeEnd("excludeDownload");

        if (Soup.GET['puzzle'])
            $.getData('words/' + Soup.GET['puzzle'] + '.txt', this.channel);
        else
            $.getData(options.url, this.channel);

        if ($.isTouchDevice) {
            document.body.overflow = "auto";
            $.on(window, 'resize', $.fullScreen);
        }

        $.fullScreen();

        this.channel.on('game.finish', _.bind(showScore, this));

        this.channel.on('word.found', _.bind(this.timer.scoreUp,
                                         this.timer,
                                         Soup.points.ON_FOUND));

        this.channel.on('word.hint', _.bind(this.timer.scoreDown,
                                        this.timer,
                                        Soup.points.ON_HINT));

        this.channel.on('data.processed', _.bind(this.setWords, this));

        initEvents(this);
    }

    var proto = Soup.prototype;

    proto.setWords = function(data, options) {
        if (_.isObject(options)) {
            this.options = _.defaults(options, this.options);

            this.channel.emit('options.change', this.options);
            this.timer.restart();
        }

        this.board.setWords(_.object(_.map(data, function(value){
            return [value.inBoard, value.inList];
        })));
    };
    
    proto.restart = function(){
        this.board.restart();
        this.timer.restart();
        this.channel.emit("game.restart");
    }
    
    proto.stop = function() {
        this.board.stop();
        this.timer.stop();
        this.channel.emit("game.stop");
    }

    function showScore() {
        this.timer.stop();
        this.ui.Modal.open('congratulation', {
            time: this.timer.time(),
            score: this.timer.getScore()
        });
    }

    function setFontSize(self, amount) {
        return function(event) {
            event.preventDefault();
            self.board.options.fontSize += amount;
            self.board.redraw();
        }
    }


    function initEvents(self) {
        $.on($.$("show-help"), "click", function(e){
            e.preventDefault();
            self.ui.Modal.open("help");
        });

        $.on($.$("restart"), "click", function(e){
            e.preventDefault();
            self.restart();
        });

        $.on($.$("font-size-up"), "click", setFontSize(self, 1));
        $.on($.$("font-size-down"), "click", setFontSize(self, -1));
    }

    Soup.points = points;
    
    Soup.GET = $.parseQueryString();

    return Soup;
});
