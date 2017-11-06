/* global UST, options, UST */
/* eslint-disable eqeqeq */
/* eslint-disable no-unused-vars */
/* eslint-disable no-mixed-operators */

/***
 UI-realted javaScript
***/

//Center absolute function
jQuery.fn.center = function () { this.css("position", "absolute"); this.css("left", (jQuery(window).width() - jQuery(this).outerWidth()) / 2 + jQuery(window).scrollLeft()) + "px"; return this; };
jQuery.fn.centerv = function () { this.css("position", "absolute"); this.css("top", (jQuery(window).height() - jQuery(this).outerHeight()) / 2 + jQuery(window).scrollTop()) + "px"; return this; };

var UST = window.UST ? window.UST : window.UST = {};

/*** Load click sound ***/
var clickSound = new Audio("images/click.mp3");

jQuery(function () {
    
    UST.UI = {};

    var $recordList = jQuery('#recordList');
    var $title = jQuery('#recordListTitle', '#recordList');
    var $rangeFilter = jQuery('#rangeFilter');
    var $sessionControls = jQuery('#sessionControls');
    var $autoplayMode = jQuery('#autoplayMode');
    var $elementsNotInLiveMode = $sessionControls.add($rangeFilter).add($autoplayMode);

    UST.UI.showRecordsList = function () {

        jQuery('tr', $recordList).removeClass('selected');
        $recordList.center();
        $recordList.fadeIn(300);

        // If live mode is selected
        if (options.liveSelected === true) {
            $elementsNotInLiveMode.hide();
            $title.text('Live sessions');
        } else {
            $elementsNotInLiveMode.show();
            $title.text('Recorded sessions');
        }
    };

    /*** Settings ***/
    jQuery(document).on('click', '.ust_dialog #close', function () {
        jQuery(this).parent().fadeOut(300);
    });

    jQuery('#show_settings').click(function (e) {
        e.preventDefault();
        UST.loadSettings().then(UST.showSettings);
    });

    /*** Uniformjs ***/
    jQuery("select,input").uniform();

    /*** Title tooltip ***/
    setTimeout(() => {
        jQuery("*[title]").qtip({
            content: {
                attr: 'title'
            },
            style: {
                classes: 'qtip-rounded qtip-red tooltip'
            },
            position: {
                target: 'mouse',
                adjust: {
                    y: 20,
                    x: 20
                },
                viewport: jQuery(window)
            }
        });
    }, 500);

    jQuery(".opt").qtip({
        content: {
            attr: 'title'
        },
        style: {
            classes: 'qtip-rounded qtip-red tooltip'
        },
        position: {
            my: 'top center',
            at: 'bottom center',
            adjust: {
                y: 5
            }
        }
    });

    /*** Update client info in the bottom bar ***/
    jQuery(document).on('click', '#recordList button', function () {
        UST.Records.prepare(
            jQuery(this).attr('data-recordid'),
            jQuery(this).attr('data-page'),
            jQuery(this).attr('data-resolution'),
            jQuery(this).attr('data-clientpageid')
        );
        UST.UI.setRecordInfo(jQuery(this));
    });

    // Toggle
    jQuery('#recordInfo').click(function () {
        jQuery(this).toggleClass('active');
    });

    jQuery('#heatmapMenu h2').click(function () {
        var $menu = jQuery(this).parent();
        $menu.toggleClass('minimized');
        jQuery("select,input", $menu).uniform();
    });

    /*** Set info in the button bar ***/
    UST.UI.setRecordInfo = function (playButton) {
        var parent = playButton.parent().parent();

        jQuery('#userFlag').html(parent.find('.ip img').clone());
        jQuery('#resolutionInfo').text(options.resolution.replace(' ', 'x') + ' ');
        jQuery('#resolutionInfo').append(parent.find('.browser').html());
        jQuery('#urlInfo').text(options.url);
        jQuery('#dateInfo').text(parent.find('.date').text());
        jQuery('#userTags').text(parent.find('.tags').text());

        var clientID = parent.attr('data-id');
        
        // Add the pages history to the session
        UST.API.getRecordList(clientID).then(UST.Records.setRecordsList);

        // Mark the session as watched
        playButton.parent().addClass('watched');
        UST.API.setRecordingWatched(clientID);
    }

    /*** Click on "Skip to next page ***/
    jQuery('button#nextPage').click(function () {

        // Stop playing the current recording
        if (jQuery('#play').text() === '| |')
            jQuery('#play').trigger('click');

        UST.Records.goToNextRecord();
    });

    /*** Skip pauses ***/
    if(localStorage.skipPauses === "true") {
        jQuery('#skipPauses').prop('checked', true);
    }
    jQuery('#skipPauses').change(function() {
        localStorage.skipPauses = jQuery(this).is(':checked');
    });

    /*** Bind click events for pagesHistory ***/
    jQuery('#pagesHistory').on('click', 'div', function() {
        // Stop playing the current recording
        if (jQuery('#play').text() === '| |')
            jQuery('#play').trigger('click');
        
        // Prepare the record based on this
        UST.Records.prepare(
            jQuery(this).attr('data-id'),
            jQuery(this).attr('data-url'),
            jQuery(this).attr('data-resolution'),
            jQuery(this).attr('data-clientpageid')
        );
    });

    /*** Sort order ***/
    // Default values
    localStorage.order = localStorage.order || 'DESC';
    jQuery('#recordList th:contains("Date")').addClass('orderedBy ' + localStorage.order);

    // Change sorting on table header click
    jQuery('#recordList th').click(function () {

        switch (jQuery(this).text()) {
            case 'Date':
                jQuery(this).removeClass('ASC DESC');
                localStorage.order = localStorage.order === 'DESC' ? 'ASC' : 'DESC';
                jQuery(this).addClass(localStorage.order);
                break;
        }

        UST.API.populateClientsList(0);
    });

    // Start autoplay mode
    jQuery("#autoplayMode").click(function startAutoplay() {
        var selectedRecord = jQuery('#recordList tr.selected');

        if(selectedRecord.length !== 1) {
            alertify.alert("Please <b>select</b> the <b>oldest recording</b> to start the playback from.");
            return;
        }

        // 
        var clientsList = [];
        
        selectedRecord.prevAll('tr[data-id]').each((index, el) => {
            var $el = jQuery('.playButton button', jQuery(el));
            clientsList.push({
                id: $el.attr('data-recordid'),
                page: $el.attr('data-page'),
                res: $el.attr('data-resolution'),
                clientpageid: $el.attr('data-clientpageid'),
                button: $el
            });
        });

        options.autoplayMode = true;
        options.autoplayList = clientsList;
        jQuery('.playButton button', selectedRecord).trigger('click');
    });
});
