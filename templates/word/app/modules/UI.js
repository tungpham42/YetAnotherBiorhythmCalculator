define(
[
    './WordList',
    'ModalWindow',
    './EventBus',
    './Utils',
    'text!../assets/template/tmpl.html',
    'text!../assets/template/template.html',
    'i18n!../nls/wordsearch',
    'underscore'
],

function(WordList, Modal, eventBus, $, tmpl, tmpl2, locale) {

    var template = _.template(tmpl);
    var rAnchor = /<a href=.*/i;

    function UI(options) {
        var self = this;
        initialize();

        this.Modal = Modal;
        this.container = document.getElementById(options.container);
        this.container.innerHTML = template({uid: options.uid});
        this.channel = eventBus.channel(options.uid);
        this.wordlist = new WordList(options);
        
        $.addClass(document.body, ! options.showForm ? "hide-form" :"");

        this.channel.on('options.change', _.bind(showPuzzleDescription));
        this.channel.on('board.resize', _.bind(resize, this));
        this.channel.on('board.created', function() {
            setTimeout(function() {
                $.removeClass(self.container.parentNode, "loading"); 
                $.removeClass(document.body, "loading"); 
            }, 500);
        });

        initEvents(this, options);
    }

    function showPuzzleDescription(options) {
        if (! options.puzzleDescription) return;

        var elem = $.$('puzzle-description');
        $.removeClass(elem, "hide");
        if (elem) elem.innerHTML = unescape(options.puzzleDescription.replace(/[+]/g, ' '));
    }

    function resize(size) {
        this.container.style.width = size + this.wordlist.list.offsetWidth +10+ 'px';
    }

    var initialize = _.once(function () {
        var div = document.createElement('div');
        div.innerHTML = _.template(tmpl2)({_: locale});

        // insert template nodes in the DOM
        while (div.children.length) {
            document.body.appendChild(div.children[0]);
        }

        // initialize modal window
        Modal.init();
    });

    function requestHint(text, channel) {
        var word = text.replace(rAnchor, '');
        channel.emit('word.request.hint', $.trim(word));
    }

    function initEvents(self, options) {
        $.on($.$('html5-wordsearch-list-'+options.uid), 'click', function(e){
            e.preventDefault();
            var elem = e.target || e.srcElement;

            if (elem.nodeName == 'A') {
                requestHint(elem.parentNode.innerHTML, self.channel);
            }
        });
        
        $.on(document, 'submit', function() {
            Modal.close();
            self.channel.emit('form.sent');
        });
    }

    return UI;
});
