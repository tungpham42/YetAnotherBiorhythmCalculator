define(
[
    './EventBus',
    './Utils',
    'text!../assets/template/description.html',
    'underscore'
],
function(eventBus, $, tmpl){

    var template = _.template(tmpl);

    function Description(options) {
        this.descriptions = [];
        this.enabled = options.showDescriptions;
        this.description = $.$('html5-wordsearch-description-'+options.uid);

        var channel = eventBus.channel(options.uid);

        channel.on('data.processed', _.bind(getDescriptions, this))

        channel.on('game.restart', _.bind(clearDescription, this));
        channel.on('word.found', _.bind(showDescription, this));
        channel.on('word.hint', _.bind(showDescription, this));
        channel.on('board.resize', _.bind(setPosition, this));
        channel.on('options.change', _.bind(setOptions, this));
    }

    function setOptions(options) {
        this.enabled = options.showDescriptions;
    }

    function clearDescription() {
        this.description.innerHTML = "";
    }

    function getDescriptions(data) {
        this.descriptions = _.object(_.map(data, function(obj) {
            return [(obj.inList || obj.inBoard).toLowerCase(), obj];
        }));
    };

    function showDescription(word) {
        word = word.toLowerCase();
        if (!this.enabled || !(word in this.descriptions))
            return;

        if (!this.descriptions[word].description)
            return clearDescription.call(this);

        this.description.innerHTML = template({
            word: this.descriptions[word].inList,
            description: this.descriptions[word].description
        });
    }

    function setPosition(size) {
        this.description.style.top = size + 10 + 'px';
        this.description.style.width = size + 'px';
    }

    return Description;
});
