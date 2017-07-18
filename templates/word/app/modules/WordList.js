define(
[
    './EventBus',
    './Utils',
    'text!../assets/template/word-list.html',
    'i18n!../nls/wordsearch',
    'underscore'
],
// Modulo encargado de crear y mantener actulizada la lista de palabras
function(eventBus, $, tmpl, locale){

    var template = _.template(tmpl);

    function WordList(options) {
        this.list = $.$('html5-wordsearch-list-'+options.uid);
        this.options = options;
        var channel = eventBus.channel(options.uid);

        channel.on('board.resize',  _.bind(setListPosition, this));
        channel.on('word.found',    _.bind(crossWord, this, "good"));
        channel.on('word.hint',    _.bind(crossWord, this, "bad"));
        channel.on('board.created', _.bind(drawWordList, this));
        channel.on('options.change', _.bind(hideSolveButtons, this));
    }

    function hideSolveButtons(options) {
        var action = !options.showSolveButton ? "addClass" : "removeClass"
        $[action](document.body, "disable-hints");
    }

    function setListPosition(size) {
        this.list.style.left = size + 10 + 'px';
        this.list.style.height = size + 'px';
    }

    function drawWordList(data) {
        this.words = _.invoke(data.words, "toLowerCase");
        this.list.innerHTML = template({
            words: data.words,
            locale: locale,
            uid: this.options.uid
        });
    }

    function crossWord(klass, word) {
        word = word.toLowerCase();
        if (_.indexOf(this.words, word) === -1)
            return;

        var id = 'html5-wordsearch-' + this.options.uid + "-" + replaceSpace(word),
            element = $.$(id);

        element.className = 'crossed ' + klass;

        this.words.splice(_.indexOf(this.words, word), 1);
    }

    function replaceSpace(word) {
        return word.replace(/ /g, '-');
    }

    return WordList;
});
