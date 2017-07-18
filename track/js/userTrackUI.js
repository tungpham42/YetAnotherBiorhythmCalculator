/***
 UI-realted javaScript
***/

//Center absolute function
jQuery.fn.center = function () { this.css("position", "absolute"); this.css("left", Math.max(0, (jQuery(window).width() - jQuery(this).outerWidth()) / 2 + jQuery(window).scrollLeft()) + "px"); return this; };
jQuery.fn.centerv = function () { this.css("position", "absolute"); this.css("top", Math.max(0, (jQuery(window).height() - jQuery(this).outerHeight()) / 2 + jQuery(window).scrollTop()) + "px"); return this; };

function showSettings() {
    jQuery('#delayRange').val(settings.delay).trigger('change');
    jQuery('#maxMoves').val(settings.maxMoves).trigger('change');
    jQuery('#staticWebsite').prop('checked', settings.static == "true");
    jQuery('#recordClicks').prop('checked', settings.recordClicks == "true");
    jQuery('#recordMove').prop('checked', settings.recordMoves == "true");
    jQuery('#recordKey').prop('checked', settings.recordKey == "true");
    jQuery('#serverPath').val(settings.serverPath).trigger('change');
    jQuery('#ignoreGET').val(eval(settings.ignoreGET)).trigger('change');
    jQuery('#percentangeRecorded').val(settings.percentangeRecorded).trigger('change');
    
    jQuery('#settings').center();
    jQuery('#settings').centerv();
    jQuery('#settings').fadeIn(300);
    jQuery.uniform.update();
}

function showRecordsList() {
    jQuery('#recordList tr').removeClass('selected');
    jQuery('#recordList').center();
    jQuery('#recordList').fadeIn(300);
}

/*** Minimize header bar if user doesn't need it atm ***/
function minimizeIfNeeded() {

    if (options.what == 'record' && lastPosY > 50)
        if (Date.now() - lastMouseMove > 3200)
            jQuery('#header').addClass("minified");

}

var lastMouseMove = Date.now();
var lastPosY = 1000;

// !Disable for now
// setInterval(minimizeIfNeeded, 3200);

jQuery(document).mousemove(function (e) {

    //Count movement only if it's in the top area
    if (e.pageY < 50) {
        lastMouseMove = Date.now();
        jQuery('#header').removeClass("minified");
    }

    lastPosY = e.pageY;
});

/*** Load click sound ***/
var clickSound = new Audio("images/click.mp3");

jQuery(function () {
    
    /*** Settings ***/
    jQuery('.ust_dialog #close').click(function () {
        jQuery(this).parent().fadeOut(300);
    });

    jQuery('#show_settings').click(function (e) {
        e.preventDefault();
        loadSettings();
        showSettings();
    });

    jQuery('#delayRange').on('change mousemove', function () {
        jQuery('#range_value').text(jQuery(this).val() + 'ms');
    });

    jQuery('#maxMoves').on('change mousemove', function () {
        jQuery('#range_value2').text(jQuery(this).val());
    });
    
    jQuery('#percentangeRecorded').on('change mousemove', function () {
        jQuery('#range_value3').text(jQuery(this).val() + '%');
    });

    jQuery('#save_settings').click(function () {
        saveSettings();
    });

    if (localStorage.censorIP == 'false') {
        censorIP = false;
        jQuery('#censorIP').prop('checked', false);
    }
    jQuery('#censorIP').change(function () {

        if (!jQuery(this).is(':checked'))
            localStorage.censorIP = 'false';
        else
            localStorage.censorIP = 'true';
    });

    /*** Uniformjs ***/
    jQuery("select,input").uniform();

    /*** Title tooltip***/
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
                y: 5,
            }
        }
    });

    /*** Update client info in the bottom bar ***/
    jQuery(document).on('click', '#recordList button', function () {
        userTrackRecord.prepare( jQuery(this).attr('data-recordid'),
                                 jQuery(this).attr('data-page'),
                                 jQuery(this).attr('data-resolution')
                               );
        setRecordInfo(jQuery(this));
    });

    // Toggle
    jQuery('#recordInfo').click(function (){
        jQuery(this).toggleClass('active');
    });

    /*** Set info in the button bar ***/
    function setRecordInfo(selectButton) {
        var parent = selectButton.parent().parent();

        jQuery('#userFlag').html(parent.find('.ip img').clone());
        jQuery('#resolutionInfo').text(options.resolution.replace(' ', 'x') + ' ');
        jQuery('#resolutionInfo').append(parent.find('.browser').html());
        jQuery('#urlInfo').text(options.url);
        jQuery('#dateInfo').text(parent.find('.date').text());
        jQuery('#userTags').text(parent.find('.tags').text());

        // Add the pages history to the session
        userTrackAjax.getRecordList(parent.attr('data-id'));
    }

    /*** Click on "Skip to next page ***/
    jQuery('button#nextPage').click(function () {

        // Stop playing the current recording
        if (jQuery('#play').text() == 'Stop')
            jQuery('#play').trigger('click');

        userTrackAjax.getNextRecord(options.lastid);
    });

    /*** Bind click events for pagesHistory ***/
    jQuery('#pagesHistory').on('click', 'div', function() {
        // Stop playing the current recording
        if (jQuery('#play').text() == 'Stop')
            jQuery('#play').trigger('click');
        
        // Prepare the record based on this
        userTrackRecord.prepare(jQuery(this).attr('data-id'),
                                jQuery(this).attr('data-url'),
                                jQuery(this).attr('data-resolution'));
    });

    /*** Sort order ***/
    //Default values
    localStorage.order = localStorage.order || 'DESC';
    jQuery('#recordList th:contains("Date")').addClass('orderedBy ' + localStorage.order);

    //Change sorting on table header click
    jQuery('#recordList th').click(function () {

        switch (jQuery(this).text()) {
            case 'Date':
                jQuery(this).removeClass('ASC DESC');
                localStorage.order = localStorage.order == 'DESC' ? 'ASC' : 'DESC';
                jQuery(this).addClass(localStorage.order);
            break;

        }

        userTrackAjax.populateClientsList(0);

    });
});