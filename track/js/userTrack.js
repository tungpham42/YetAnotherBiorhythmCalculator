/***
 Script  :: userTrack 
 Version :: 2.3.2
 Date    :: 13 August 2017
 Author  :: Cristian Buleandra | www.tips4design.com
 Codecanyon profile: http://codecanyon.net/user/tips4design
***/

/* global UST, DEBUG, userTrackDownload */

// Global variables
var record = {};
var inPlaybackMode = false;
var progressBar;
var settings = {
    delay: 200,
    recordClicks: true,
    recordMoves: true,
    static: false,
    maxMoves: 300,
    censorIP: true
};

UST.firstStaticX = 0;
UST.recordPlaying = false;
UST.skipToPercentage = undefined;
UST.autoPopulateTimeout = undefined;
UST.autoPopulateInterval = 3000; // how often to auto-update live recordings list
UST.liveDataLoadDelay = 2000; // how often to try load extra data for current client being played
UST.liveGetExtraDataTimeout = undefined; // Handler to the current timeout waiting to get extra data
UST.updatePagesHistoryDelay = 3000; // how often to auto-update pages history
UST.updatePagesHistoryTimeout = undefined; // Handler to current timeout for getting pages history

// Object to store selected filter values
var options = {
    url: '',
    resolution: '-1',
    what: localStorage.what !== undefined ? localStorage.what : 'movements',
    domain: '',
    radius: localStorage.radius ? +localStorage.radius : 30,
    liveSelected: false,
    autoplayMode: false,
    autoplayList: []
};

jQuery(function () {
    
    // Cache jQuery element selectors.
    var $iframe = jQuery('#heatmapIframe');
    var $heatmapWrap = jQuery('#heatmapWrap');
    var $pageDropdown = jQuery('#page');

    // Alertify settings
    alertify.logPosition("bottom right");
    alertify.delay(1500);

    progressBar = jQuery('#progressBar div');

    // Get domain to show data for from localStorage
    if (localStorage.getItem('domain') !== null)
        options.domain = localStorage.getItem('domain');

    if(!window.DIRECT_PLAYBACK) {
        // Load timezone
        UST.API.getUtcOffset().then(offset => {
            localStorage.serverUtcOffset = offset;
        });

        // Load list of different pages from the current domains
        UST.API.getPages().then(setPagesList);
    }

    /**
     * Popuates the pages menu;
     */
    function setPagesList (data) {
        $pageDropdown.html('');

        for (var v in data)
            $pageDropdown.append('<option value="' + data[v] + '">' + data[v] + '</option>');

        // First page selected if no other defaults will be set
        if(data[0]) jQuery('option[value="' + data[0] + '"]', $pageDropdown).attr('selected', true);

        var defaultUrl = jQuery('option:first', $pageDropdown).val() || '/';

        // Restore current screen menu
        if (localStorage.getItem('what') !== null) {
            options.what = localStorage.what;
            options.liveSelected = localStorage.liveSelected === 'true';
            jQuery('.opt').removeClass('selected');

            // If 'record' is the data-value, get the second one if live is selected
            var recordIndex = localStorage.liveSelected === 'true' ? 1 : 0;
            jQuery('.opt[data-value=' + options.what + ']').eq(recordIndex).addClass('selected');

            // If it is recording we have to trigger a click to show the menu
            if(options.what === 'record')
                jQuery('.opt.selected').trigger('click');
        }

        jQuery('#loading').text("Loading webpage");

        //Only set absolute path for cross-domain
        var absolutePath = '';
        if (options.domain !== '')
            absolutePath = '//www.' + options.domain;
        
        setIframeSource(absolutePath + defaultUrl);
        options.url = defaultUrl;
        jQuery("select,input").uniform();
        $pageDropdown.trigger('change');
    }

    // Delete record
    jQuery('#deleteRecord').click(function () {
        alertify.confirm("This will delete the recording of this page only.", function() {
            var id = options.recordid;
            if (UST.API.deleteRecord(id) !== 0) {
                jQuery("#records option[value=" + id + "]").remove();
            }
        });
    });

    // Redraw heatmap on resolution change
    jQuery('#resolution').change(function () {
        DEBUG && console.log("resolution changed");

        options.resolution = jQuery(this).val();
        UST.Heatmap.clean();

        if (options.resolution != -1) {
            var res = jQuery(this).val().split(' ');
            iframeFit(res[0], res[1]);
        } else {
            iframeFit();
        }
    });

    /**
     * Redraws the heatmap to match the current settings and filters
     */
    function updateHeatmap () {
        // Only set absolute path for cross-domain
        var absolutePath = '';
        if (options.domain !== '')
            absolutePath = '//www.' + options.domain;

        setIframeSource(absolutePath + options.url);
      
        // After changing the page get the new available resolutions
        UST.API.getResolutions().then(updateResolutions);
    }

    function updateResolutions (data) {
        DEBUG && console.log('resolutions', data);

        var $resolution = jQuery('#resolution');
        $resolution.html('<option value="-1" selected>Any</option>');
        
        data = data.map(x => x.split(' '));
        
        for (var v in data) {
            $resolution.append('<option value="' + data[v].join(' ') + '">' + data[v][0] + ' x ' + data[v][1] + '</option>');
        }
        
        jQuery('option', $resolution).each(function () {

            // Remove all options with the same width
            var curEl = jQuery(this);
            var curWidth = +curEl.val().split(' ')[0];

            // Continue if resolution is set to "Any" or is removed
            if (curWidth < 0) return 1;

            // Don't display resolution, but width
            curEl.text(curWidth);

            var index = curEl.index();

            for (var i = index + 1; i < jQuery('option', $resolution).length; ++i) {

                var el = jQuery('option', $resolution).eq(i);

                if (+el.val().split(' ')[0] === curWidth) {
                    jQuery('option', $resolution).eq(i).val('-2');
                }
            }

            jQuery('option[value="-2"]', $resolution).remove();
        });

        // Sort values
        var allOptions = jQuery('option', $resolution);
        var selected = $resolution.val();

        allOptions.sort(function(a,b) {
            if (+a.text > +b.text) return -1;
            if (+a.text < +b.text) return 1;
            return 0;
        });

        $resolution.empty().append(allOptions);
        $resolution.val(selected);
    }

    // Redraw heatmap on page change
    $pageDropdown.change(function () {
        // Do nothing if the list is empty
        if (jQuery(this).children().length === 0)
            return;
            
        DEBUG && console.log("page changed to", jQuery(this).val(), jQuery(this));

        // Save the new page URL
        options.url = jQuery(this).val();

        updateHeatmap();
    });

    // Redraw heatmap on statistics type change
    jQuery('.opt').click(function () {
        // Clear previous displayed statistics
        UST.Records.reset();
        
        // Scroll to top if we came from recordings
        if(options.what === 'record')
            UST.Records.scrollTo(0,0);
        
        options.what = localStorage.what = jQuery(this).attr('data-value');
        options.liveSelected = localStorage.liveSelected = jQuery(this).attr('data-filter') === 'live';
        jQuery('.opt').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('#heatmapWrap').css('opacity', 0).hide();
        if (options.what === 'record') {
            UST.API.populateClientsList(0);
            UST.UI.showRecordsList();
            jQuery('#recordControls button').attr('disabled', true);
            jQuery('#cursor,#recordControls,#heatmapIframe').animate({ opacity: 1 }, 300);
            jQuery('#windowWidth').slideUp(200);
            jQuery('#progressBar').slideDown(200);
            jQuery('#heatmapMenu').fadeOut(200);
            jQuery('#loading').hide(100);
            jQuery('#downloadHeatmap').hide(100);
        }
        else {
            // Hide records list if dialog is shown
            jQuery('#recordList #close').trigger('click');
            jQuery('#loading').show().text("Retrieving " + options.what + " statistics...");
            jQuery('#cursor,#recordControls').animate({ opacity: 0 }, 300);
            jQuery('#windowWidth').slideDown(200);
            jQuery('#progressBar').slideUp(200);
            jQuery('#heatmapMenu').fadeIn(200);
            jQuery('#resolution').trigger('change');
            jQuery('#downloadHeatmap').show(100);
        }
    });

    function checkIframePathChanged (path) {
        DEBUG && console.log("Check path changed called: ", options.url, path);
        if(options.url !== path) {
            options.url = path;
            if(jQuery(`option[value="${path}"]`, $pageDropdown).length > 0) {
                $pageDropdown.val(path).trigger('change');
            } else {
                console.log('Could not find heatmap data for this exact path: ', path);
            }
        } else {
            // Else we just continue generating the heatmap
            iframeFit(undefined, undefined);
        }
    }

    // Resize iframe
    function iframeFit(width, height) {
        DEBUG && console.log('iframeFit', width, height);

        if (width === undefined)
            width = jQuery(window).width() - 17;
      
        if (height === undefined)
            height = jQuery(window).height() - jQuery('#header').outerHeight();

        $iframe.height(height);
        $iframe.width(parseInt(width));
        $iframe.center();

        // Only show heatmap if not in record playback mode
        if (options.what === 'record') {
            jQuery('#loading').fadeOut(200);
            return;
        }

        // Place heatmap inside wrap, get iframe contents size
        UST.iframe.contentWindow.postMessage(JSON.stringify({ task: 'SZ' }), "*");
    }

    // Make global alias
    window.iframeFit = iframeFit;
    
    $iframe.load(function () {
        DEBUG && console.log("iframe loaded");

        if (inPlaybackMode)
            return;

        if (options.stopLoadEvents) {
            options.stopLoadEvents = false;
            return;
        }

        // Check if the path was changed  by clicking
        UST.iframe.contentWindow.postMessage(JSON.stringify({ task: 'PTH' }), "*");
    });

    // Html5 postMessage to allow cross-domain irame interactions
    UST.iframe = document.getElementsByTagName('iframe')[0];

    jQuery('#recordControls button#play').click(function () {
        if (UST.recordPlaying) {
            inPlaybackMode = UST.recordPlaying = false;
            jQuery(this).text('►');
            return;
        }
        jQuery(this).text('| |');
        inPlaybackMode = UST.recordPlaying = true;    

        // Scroll iframe to top
        UST.iframe.contentWindow.postMessage(JSON.stringify({ task: 'SCR', top: 0, left: 0, delay: 0 }), "*");

        // Ask iframe to create classes object
        UST.iframe.contentWindow.postMessage(JSON.stringify({ task: 'CSS' }), "*");

        if (progressBar.css('width') === '0%' || progressBar.css('width') == '0px')
            UST.Records.playFrom(0);
        else
            progressBar.animate({ width: '0%' }, 500, function () { UST.Records.playFrom(0); });
    });

    // Skip record to clicked time
    jQuery('#progressBar').click(function (e) {
        UST.skipToPercentage = Math.floor(100 * (e.pageX - jQuery(this).offset().left) / jQuery(this).width());
    });

    function handleIframeResponse (e) {

        // Check if JSON
        if (e.data[0] === '!' || (e.data[0] > 'A' && e.data[0] < 'z'))
            return;

        var data = jQuery.parseJSON(e.data);

        switch (data.task) {
            case 'SZ':
                DEBUG && console.log('SZ task received from iframe');
                jQuery('#heatmap').width(data.w);
                jQuery('#heatmap').height(data.h);

                // Redraw hetmap when window size is changed
                if (options.what !== 'record') {
                    // Fit heatmapWrap over iframe
                    $heatmapWrap.width($iframe.width());
                    $heatmapWrap.height($iframe.height());
                    $heatmapWrap.css('left', $iframe.css('left'));
                    $heatmapWrap.css('top', $iframe.offset().top);
                    $heatmapWrap.fadeIn(200);

                    UST.Heatmap.draw();
                }
                break;

            case 'SCROLL':
                if (options.what !== 'record') {
                    // iframe is scrolled
                    jQuery('#heatmap').css('transform', 'translate3d(' + -data.left + 'px,' + -data.top + 'px, 0px');
                    UST.Heatmap.scrollMinimap(data.top, data.left);
                }

                // Also move clickBoxes
                jQuery('.clickBox').each(function () {
                    var t = jQuery(this);
                    t.css({
                        'top': Number(t.attr('data-top')) - data.top,
                        'left': Number(t.attr('data-left')) - data.left
                    });

                });
                break;

            case 'STATIC':
                UST.firstStaticX = data.X;
                break;

            case 'PTH':
                DEBUG && console.log('PTH task received from iframe');
                checkIframePathChanged(data.p);
                break;

            case 'html2canvasAdded':
                jQuery('#loading').show().text("Generating the screenshot.");
                UST.iframe.contentWindow.postMessage(JSON.stringify({ task: 'screenshot' }), "*");
                break;

            case 'screenshot':
                userTrackDownload.start(data.img);
                break;
        }
    }

    // HTML5 postmessage to get size of iframe contents
    window.addEventListener('message', handleIframeResponse, false);
});

function bindClickToList () {
    jQuery('#recordList tr').on('click', function () {
        jQuery(this).toggleClass('selected');
    });
    jQuery('#pagination span').on('click', function () {
        var val = jQuery(this).text();
        var take = jQuery('#numberFilter select').val();
        UST.API.populateClientsList((val - 1) * take);
    });
}

// Tooltip
function updateToolTips(parentSelector) {
    if(!parentSelector) parentSelector = '';
    $(parentSelector + " *[title]").qtip({
        content: {
            attr: 'title'
        },
        style: {
            classes: 'qtip-rounded qtip-blue tooltip'
        },
        position: {
            target: 'mouse',
            adjust: {
                y: 20,
                x: 20
            },
            viewport: jQuery(window)
        },
        show: {
            delay: 600
        }
    });
}

// Change iframe src
function setIframeSource(link) {

    // Use only '//' instead of http/https
    link = link.replace('http://', '//');
    link = link.replace('https://', '//');
    
    // Match www. with current domain
    if (window.location.href.indexOf("//www.") === -1)
        link = link.replace('//www.', '//');


    jQuery('#heatmapIframe').prop('src', link);
}