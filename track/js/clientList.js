/* global UST, options, Clipboard */
jQuery(function ($) {

    /**
     * Helper class to manage the date range filter
     * @param  {jQuery} el jQuery wrap object
     */
    var rangeFilter = function (el) {

        el = el || $('body');

        var fromInput = $('input[name=from]', el);
        var toInput = $('input[name=to]', el);

        /**
         * Converts a Date to "YYYY-MM-DD"
         * @param  {Date} date
         * @return {String} converted date
         */
        function shortISO (date) {
            return date.toISOString().substring(0,10);
        }

        /**
         * Updates the form and to date inputs 
         * to display the specified range
         * @param {Date} start 
         * @param {Date} end  
         */
        this.setRange = function (start, end) {
            fromInput.val(shortISO(start));
            toInput.val(shortISO(end));
        };
    };

    var range = new rangeFilter($('#rangeFilter'));
    
    // Default value is 6 months of data
    var end = new Date();
    var start = new Date(end);
    start.setMonth(start.getMonth() - 6);

    range.setRange(start, end);

    // Update the list on filter change
    $('.filter *').on('change', function () {
        UST.API.populateClientsList(0);
    });

    var $tagFilter = $('#tagFilter');

    // Removes the given tag from the list and refresh
    function removeTag(el) {
        el.fadeOut(100, function() {
            el.remove();
            UST.API.populateClientsList(0);
        });
    }

    // Remove tags from filters if they are clicked
    $tagFilter.on('click', '.tagFilterElement', function() {
        removeTag($(this));
    });

    function addTag(tag) {
        if(tag === null || tag.length === 0) {
            alertify.alert("Tag can not be empty!");
            return;
        }

        // If tag already exists actually remove it
        var tagFound = $('.tagFilterElement .value').filter(function(index, el) { return el.innerText === tag; });
        if(tagFound.length > 0) {
            // Remove the tag
            removeTag(tagFound.parent());
            return;
        }

        var $tagEl = $('<div class="tagFilterElement"><span class="value"></span> <span class="x">x</span></div>');
        $tagEl.children('.value').text(tag);
        $tagEl.hide();
        $tagFilter.append($tagEl);
        $tagEl.fadeIn(300);
        UST.API.populateClientsList(0);
    }

    // Add tags to the filter when they are clicked
    $('#recordList').on('click', '.tag', function() {
        addTag($(this).text());
        return false;
    });

    // Remove tags to the filter when they are clicked
    $('#recordList').on('contextmenu', '.tag', function() {
        var el = $(this);
        var plusButton = el.parent().find('.plus');
        var clientID = plusButton.attr('data-id');

        var tag = el.text();

        UST.API.removeTag(clientID, tag, function() {
            el.slideUp(200, function() { el.remove(); });
        });

        return false;
    });

    // Manually add a tag filter
    $('#addTagFilter').click(function() {
        alertify.prompt("Tag value", function(tag) {
            addTag(tag);
        });
    });

    // Add tag to client
    $('#recordList').on('click', '.addTag', function() {
        var plusButton = $(this);
        var clientID = plusButton.attr('data-id');
        alertify.prompt("Tag value", function(tag) {
            UST.API.addTag(clientID, tag, function() {
                var span = $('<span class="tag"></span>');
                span.text(tag);
                span.hide();
                plusButton.parent().append(span);    
                span.slideDown(100);
            });
        });
    });
    // var clientID = findGetParameter('c');
    // var recordID = findGetParameter('i');
    // var clientPageID = findGetParameter('x');
    // var page = findGetParameter('p');
    // var resolution = findGetParameter('r');
    // var key = findGetParameter('k');

    // Share link button
    $('#recordList').on('click', '.shareRecordButton', function() {
        var $this = $(this);
        var $tr = $this.closest('tr');
        var clientID = $tr.attr('data-id');


        UST.API.getShareToken(clientID).then(token => {
            // Output the link
            var playButton = $this.prev('button');
            var resolution = $('.resolution', $tr).text();

            var path = window.location.href.replace('userTrack.html', 'play.html');
            var params = [
                'c=' + encodeURIComponent(clientID),
                'i=' + encodeURIComponent(playButton.attr('data-recordid')),
                'x=' + encodeURIComponent(playButton.attr('data-clientpageid')),
                'p=' + encodeURIComponent(playButton.attr('data-page')),
                'r=' + encodeURIComponent(resolution),
                'k=' + encodeURIComponent(token),
                'd=' + encodeURIComponent(options.domain)
            ];
            var link = path + '?' + params.join('&');

            // Display a prompt with the link
            // Use Clipboard.js for better UX
            // We have to dynamically create/destroy the clipboard.js instance
            var clipboard;
            alertify.defaultValue(link).okBtn("Copy to clipboard").prompt("<b>Share link</b>. Anyone with this link can view this recording.", function() {
                alertify.okBtn("Ok");
                alertify.success('Link copied to cliboard!');
                setTimeout(function() {
                    clipboard && clipboard.destroy();
                }, 200);
            }, function() {
                clipboard && clipboard.destroy();
                alertify.okBtn("Ok");
            });

            var $dialog = jQuery('.alertify');

            jQuery('input', $dialog).attr('id', 'clipboardInput').attr('value', link);
            var $okButton = jQuery('.ok', $dialog);
            $okButton.attr('data-clipboard-target', '#clipboardInput');
            clipboard = new Clipboard($okButton[0]);
        });
    });

    $('#deleteRecords').click(function () {
        var count = $('#recordList tr.selected').length;
        if (count === 0) {
            alertify.alert("No records selected!");
            return;
        }

        alertify.confirm("Are you sure you want to delete the selected records?",
            function () {
                // Delete all data for selected clients
                $('#recordList tr.selected').each(function () {
                    var id = $(this).attr('data-id');
                    UST.API.deleteClient(id, this);
                });
                alertify.log("Deleting " + count + " client" + (count > 1 ? 's' : ''));
            });
    });

    $('#cleanDatabase').click(function () {
        alertify.confirm("This will delete ALL data stored for this domain. Are you sure?",
            function() {
                UST.API.cleanDataForDomain(options.domain);
            });
    });

    $('#deleteZeroRecords').click(function () {
        UST.API.deleteZeroRecords(options.domain);
    });
});
