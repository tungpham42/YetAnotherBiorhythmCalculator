define(
[
    './EventBus',
    './Utils',
    'underscore',
    'json'
]
, function(eventBus, $){

    function Downloader(options) {
        this.options = options;
        var channel = eventBus.channel(options.uid);
        createDownloadForm.call(this);

        // show download button
        this.button = $.$("download-puzzle");
        this.button.style.display = "block";

        $.on(this.button, 'click', _.bind(download, this._form));

        channel.on('options.change', _.bind(setOptions, this));
        channel.on('board.created', _.bind(saveData, this));
    }

    function setOptions(options) {
        this.options = options;
    }

    function download(e) {
        e.preventDefault();
        this.submit();
    }

    function saveData(data) {
        data.solutions = data.solutions;
        data.fontSize = this.options.fontSize;
        data.title    = this.options.name || $.parseQueryString()['puzzle'] || 'default';
        data.description = this.options.puzzleDescription;
        data.words = data.words;
        this._textarea.value = JSON.stringify(data);
    }

    function createDownloadForm() {
        var form     = document.createElement("form"),
            textarea = document.createElement("textarea");

        this._form = form;
        this._textarea = textarea;

        form.id = "download-form";
        form.action = "download/";
        form.method = "post";
        form.style.display = 'none';

        textarea.name = "data";
        form.appendChild(textarea);
        document.body.appendChild(form);
    }

    return Downloader;
});
