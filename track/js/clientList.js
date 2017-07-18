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
        this.setRange =  function (start, end) {
            fromInput.val(shortISO(start));
            toInput.val(shortISO(end));
        }
    };

    var range= new rangeFilter($('#rangeFilter'));
    
    // Default value is 6 months of data
    var end = new Date();
    var start = new Date(end);
    start.setMonth(start.getMonth() - 6);

    range.setRange(start, end);

    // Update the list on filter change
    $('.filter *').on('change', function (){
        userTrackAjax.populateClientsList(0);
    });

    var $tagFilter = $('#tagFilter');

    // Removes the given tag from the list and refresh
    function removeTag(el) {
        el.fadeOut(100, function(){
            el.remove();
            userTrackAjax.populateClientsList(0);
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
        var tagFound = $('.tagFilterElement .value').filter(function(index, el) { return el.innerText == tag});
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
        userTrackAjax.populateClientsList(0);
    };

    // Add tags to the filter when they are clicked
    $('#recordList').on('click', '.tag', function(e) {
        addTag($(this).text());
        return false;
    });

    // Remove tags to the filter when they are clicked
    $('#recordList').on('contextmenu', '.tag', function(e) {
        var el = $(this);
        var plusButton = el.parent().find('.plus');
        var clientID = plusButton.attr('data-id');
        console.log(clientID);
        var tag = el.text();

        userTrackAjax.removeTag(clientID, tag, function() {
            el.slideUp(200, function() { el.remove()});
        });

        return false;
    });

    // Manually add a tag filter
    $('#addTagFilter').click(function() {
        alertify.prompt("Tag value", function(tag, ev) {
            addTag(tag);
        });
    });

    // Add tag to client
    $('#recordList').on('click', '.addTag', function() {
        var plusButton = $(this);
        var clientID = plusButton.attr('data-id');
        alertify.prompt("Tag value", function(tag, ev) {
            userTrackAjax.addTag(clientID, tag, function() {
                var span = $('<span class="tag"></span>');
                span.text(tag);
                span.hide();
                plusButton.parent().append(span);    
                span.slideDown(100);
            });
        });
    });

    $('#deleteRecords').dblclick(function () {

        // Delete all data for selected clients
        if ($('#recordList tr.selected').length !== 0) {
            $('#recordList tr.selected').each(function () {
                userTrackAjax.deleteClient($(this).attr('data-id'), this);
            });
            return;
        } else {
            alert("No records selected!");
        }
    });

    $('#cleanDatabase').dblclick(function () {
        userTrackAjax.cleanDataForDomain(options.domain);
    });

    $('#deleteZeroRecords').dblclick(function () {
        userTrackAjax.deleteZeroRecords(options.domain);
    });
});