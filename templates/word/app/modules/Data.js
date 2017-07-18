define(
[
    './EventBus',
    './Utils',
    'underscore'
],

function(eventBus, $){
    var NL = /\r\n|\n/g;

    var types = {
        alphabet: toString,
        totalWords: toNumber,
        size: toNumber,
        showSolveButton: toBoolean,
        showDescriptions: toBoolean,
        every: toNumber,
        deduct: toNumber,
        initialScore: toNumber
    };

    function toString(value) { return String(value); }
    function toNumber(value) { return parseInt(value, 10) || 0; }
    function toBoolean(value) {
        value = value.toLowerCase()
        return value == "true" ? true : value == "false" ? false : !!toNumber(value);
    }

    // takes rawData and returns an array of object with the following format
    //   {
    //      inBoard: 'word in board',
    //      inList: 'word in list',
    //      description: 'word description'
    //   }
    //      
    // e.g.
    // 
    // English to Spanish word-search
    // 
    //  {
    //      inBoard: 'blue',
    //      inList:  'azul',
    //      description: 'as the sky'
    //  }
    function process(rawData, channel) {
        rawData = $.trim(rawData);
        var lines = rawData.split(NL);

        var data = _.compact(_.map(lines, processLine));
        var options = _.object(_.compact(_.map(lines, getOptions)));

        channel.emit('data.processed', data, options);
    }

    function isCommend(line) {
        return $.trim(line).charAt(0) === '#';
    }

    function getOptions(line) {
        if (!isCommend(line))
            return;

        var parts = line.replace('#', '').split(':');
        var key = $.trim(parts[0]);
        var value = $.trim(parts[1]);

        value = (types[key] || toString)(value);

        return [key, value];
    }

    // each line have the following format:
    // word1[:word2] | description
    function processLine(line) {
        if (isCommend(line))
            return;

        var parts = line.split('|'),
            words = parts[0].split(':');

        return {
            inBoard: $.trim(words[0]),
            inList: $.trim(words[1] || words[0]),
            description: $.trim(parts[1] || '')
        };
    }

    eventBus.on('data.arrived', process);
});
