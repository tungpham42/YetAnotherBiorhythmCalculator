define(
[
    './Utils'
],

function($) {
    'use strict';

    // como avanzar en cada dirección
var directionMap = {
        vertical: 'S_',
        horizontal: '_O',
        diagonal: 'SO NO'
    },

    reverseDirection = {
        vertical: 'N_',
        horizontal: '_W',
        diagonal: 'SW NW'
    },

    DIRECTIONS,
    ADVANCE =  {S: 1, N: -1, W: -1, O: 1, _: 0},
    TESTS = {
        S:  function(candidate, edge) {
            return candidate.start.row + candidate.word.length > edge;
        },
        O:  function(candidate, edge) {
            return candidate.start.col + candidate.word.length > edge;
        },
        N:  function(candidate) {
            return candidate.start.row - candidate.word.length < 0;
        },
        W:  function(candidate) {
            return candidate.start.col - candidate.word.length < 0;
        },
        _: function() { return false; }
    };

    function Candidate(word, size) {
        var direction = $.choice(DIRECTIONS);
        this.word = $.splitString(word);
        this.ydir = direction.charAt(0);
        this.xdir = direction.charAt(1);
        this.start = {
            col: $.randint(0, size-1),
            row: $.randint(0, size-1)
        };
    }

    var buildDirectionsArray = _.once(function (directions) {
        // ensure is array of lowercase strings
        directions = _.isArray(directions) ? directions : [];
        directions = _.invoke(_.map(directions, String), 'toLowerCase');

        var dirs = _.map(directions, function(dir){ return directionMap[dir]; }).join(" ");

        if (_.contains(directions, 'reverse'))
            dirs += _.map(directions, function(dir){ return reverseDirection[dir]; }).join(" ");

        if (! $.trim(dirs))
            dirs = "_O S_ SO NO _W N_ SW NW";

        return $.trim(dirs).split(" ");
    });

    // createGrid({
    //      words: [string, string...],
    //      size: int,
    //      alphabet: string
    // });
    function createGrid(options) {
        DIRECTIONS = buildDirectionsArray(options.directions);

        var grid = _.map(_.range(options.size), function(){ return {}; }),
            soFar = 0,
            words = options.words.slice(0),
            used  = {},
            totalWords = options.totalWords;

        var start = $.now();

        // until insert totalWords or loop more than 200ms
        while (words.length && soFar < totalWords && $.now() - start < 200) {
            var candidates = selectCandidates(words, grid, options.tries || 5);

            var best = _.max(candidates, byScore);

            // underscore.max returns any value greater than -Infinity
            if (best.score > 0) {
                soFar++;
                // insertar mejor punteado
                best.end = insertWordInGrid(best, grid);
                best.word = best.word.join("");
                used[best.word] = best;
                words.splice(_.indexOf(words, best.word), 1);   
            }
        }

        return {
            grid: fillWithRandomChars(options.alphabet, grid),
            used: used
        };
    }

    // fill empty cells with random character from alphabet
    function fillWithRandomChars(alphabet, grid) {
        var i, j, length = grid.length, alpha = $.splitString(alphabet);
        for (i = 0; i < length; i++) {
            alpha = _.shuffle(alpha);
            for (j = 0; j < length; j++) {
                grid[i][j] = (grid[i][j] || $.choice(alpha)).toUpperCase();
            }
        }

        return grid;
    }

    // return an object with n random candidates to be inserted in the grid
    function selectCandidates(words, grid, n) {
        var candidates = {}, i;

        // rank n words
        for (i = 0; i < n; i++) {
            var currentWord = $.choice(words),
                candidate = new Candidate(currentWord, grid.length);

            candidate.score = rank(candidate, grid);

            // palabra ya esta y tiene mejor puntaje que ahora
            if (candidates[candidate.word] &&
                candidates[currentWord].score > candidate.score) {
                continue;
            }

            candidates[currentWord] = candidate;
        }

        return candidates;
    }

    // used to select the best candidate
    function byScore(candidate) {
        return candidate.score;
    }

    // gives points to candidate as follow
    // 1 point if word fits in the grid
    // +1 point if word is backward
    // +1 for each character crossed
    function rank(candidate, grid) {
        var score = 1,
            edge  = grid.length,
            xdir  = candidate.xdir,
            ydir  = candidate.ydir,
            row   = candidate.start.row,
            col   = candidate.start.col;
        
        // se sale del grid
        if (TESTS[xdir](candidate, edge) || TESTS[ydir](candidate, edge))
            return 0;

        // word is backward
        if (xdir == 'W' || ydir == 'N' || 'SW SO NO NW'.indexOf(ydir+xdir) != -1)
            score += 1;

        var i, length = candidate.word.length;

        for (i = 0; i < length; i++)
        {
            var char = candidate.word[i];
            // there is a character in the cell
            if (grid[row][col] && grid[row][col] !== char)
                return 0;
            else if (grid[row][col] === char)
                score++;

            row += ADVANCE[ydir];
            col += ADVANCE[xdir];
        }

        return score;
    }
    
    // insert the candidate in the grid
    function insertWordInGrid(candidate, grid) {
        var row = candidate.start.row,
            col = candidate.start.col,
            length = candidate.word.length,
            i;
            
        for (i = 0; i < length; i++)
        {
            grid[row][col] = candidate.word[i];
            row += ADVANCE[candidate.ydir];
            col += ADVANCE[candidate.xdir];
        }

                // restar ultimo avance
        return {
            row: row - ADVANCE[candidate.ydir],
            col: col - ADVANCE[candidate.xdir]
        };
    }

    return {
        create: createGrid
    };
});
