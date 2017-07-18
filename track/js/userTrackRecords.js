/***
 Manage record playback
***/
var userTrackRecord = (function () {

    //Private variables
    var lastElement = null;
    var scroll = { left: 0, top: 0 };
    var cursor = jQuery('<img id="cursor" src="images/cursor.png"/>');
    var numberOfClicks = 0;

    //Initialization
    jQuery(function () {
        jQuery('body').append(cursor);
    });

    // Prepare and play the record given by the arguments
    function prepareRecord(id, page, res) {

        DEBUG && console.log('Prepare record: ', id, page, res);

        artificialTrigger = 1;
        fromList = 1;

        options.resolution = res;
        options.lastid = options.recordid = id;
        options.url = page;
        options.stopLoadEvents = true;

        // Set iframe size
        var res = options.resolution.split(' ');
        iframeFit(res[0], res[1]);

        // Set iframe path
        var absolutePath = '';
        if (options.domain !== '')
            absolutePath = '//' + options.domain;
        setIframeSource(absolutePath + options.url);
        
        userTrackAjax.getRecord(options.lastid);

        jQuery('#recordList').fadeOut(300);
        jQuery('#recordControls button').attr('disabled', false);
    }

    function setNextRecord(data) {
        DEBUG && console.log('Set next record: ', data);

        //If there is a next record
        if (data.id !== 0) {
            artificialTrigger = true;
            prepareRecord(data.id, data.page, data.res);
        } else { 
            //Else exit playback mode
            inPlaybackMode = false;
            alertify.alert('User has left the website.');
        }
    }

    function setCurrentRecord(data) {
        record = data;     

        setTimeout(function () { 
            jQuery('#play').trigger('click'); 

            // Highlight current page
            jQuery('#pagesHistory div').removeClass('active');
            jQuery('#pagesHistory div[data-id='+options.recordid+']').addClass('active');
        }, 500); 

        fromList = 0;
    }

    function resetElements(minimizeBar) {

        scroll = { left: 0, top: 0 };
        numberOfClicks = 0;
        jQuery('.clickBox').remove();

        //Minimize top bar before starting a replay
        if (minimizeBar === true)
            jQuery('#header').addClass("minified");
    }

    function startPlayback(data) {

        // If we start playing a record from the recordList
        if (fromList) {
            userTrackAjax.getRecord(options.recordid);
            artificialTrigger = false;
        } else {

            // If we continue to play a record
            if (artificialTrigger) {
                userTrackAjax.getRecord(options.lastid);
                artificialTrigger = false;
            }
        }
    }

    var lastP = {};
    //Play i-th action from recording
    function playRecord(i) {

        if (i === 0)
            resetElements(1);

        var p = record[i];
        if (p === undefined) {
            recordPlaying = false;
            jQuery('#recordControls button#play').text('Play');
            return;
        }
        progressBar.css({ width: Math.round((i + 1) * 100 / record.length) + '%' });

        DEBUG && console.log(p);

        // Handle idle time
        if(p.t === 'i') {
            if (i + 1 < record.length) {
                // Wait for the delay stored in p.d
                setTimeout(function () { playRecord(i + 1); }, p.d);
            }
            return;
        }

        p.x -= scroll.left;
        p.y -= scroll.top;

        // If click triggered an artificial mouse movement, skip it
        var oldP = record[i-1];
        if(i > 0 && p.t === 'm' && oldP.t === 'c' && lastP.x === p.x && lastP.y === p.y) {
            // Skip
            if (i + 1 < record.length) {
                setTimeout(function () { playRecord(i + 1); }, 0);
            }
            return;
        }

        //Set element under
        oIframe.contentWindow.postMessage(JSON.stringify({ task: 'EL', x: p.x, y: p.y }), "*");

        //Trigger hover
        oIframe.contentWindow.postMessage(JSON.stringify({ task: 'HOV' }), "*");

        // Scroll
        if (p.t == 's') {
            scrollIframe(p.x + scroll.left, p.y + scroll.top);
            if (recordPlaying) {
                if (i + 1 < record.length) {
                    setTimeout(function () { playRecord(i + 1); }, 100);
                } else {
                    //We have reached the end of the recording
                    recordPlaying = false;
                    jQuery('#recordControls button#play').text('Play');
                    userTrackAjax.getNextRecord(options.lastid);
                }
            }
        } else {
            var interpTime = settings.delay - 20;
            if(p.t === 'c' && lastP.x === p.x && lastP.y === p.y) interpTime = 5;
            cursor.animate({
                'top': p.y + jQuery('#heatmapIframe').offset().top,
                'left': p.x + jQuery('#heatmapIframe').offset().left
            }, interpTime,
                function () {

                    lastP.x = p.x;
                    lastP.y = p.y;
                    if (p.t == 'c')
                        triggerClick(p.x, p.y);
                    if (p.t == 'b') {
                        triggerValueChange(p.p, p.v, 0, i);
                        return;
                    }

                    //Skip to the clicked time
                    if (playNext !== 0) {
                        i = Math.floor(playNext / 100 * record.length);
                        playNext = 0;
                    }

                    // Play next event
                    if (i + 1 < record.length) {
                        if (recordPlaying)
                                setTimeout(function () { playRecord(i + 1); }, 0);
                    } else {
                        //We have reached the end of the recording
                        recordPlaying = false;
                        jQuery('#recordControls button#play').text('Play');
                        userTrackAjax.getNextRecord(options.lastid);
                    }
                });
        }
    }

    function triggerClick(x, y) {

        //Absolute position coordinates
        x += jQuery('#heatmapIframe').offset().left;

        //Display the click radius animation
        var circle = jQuery("<div class='clickRadius'>&nbsp;</div>");
        var radius = 30;
        circle.css('top', y).css('left', x);
        jQuery('#pageWrap').append(circle);
        circle.animate({ 'height': radius, 'width': radius, 'top': y - radius / 2, 'left': x - radius / 2, 'opacity': 0.3 }, 500,
                        function (v) {
                            circle.animate({ 'height': 2 * radius, 'width': 2 * radius, 'top': y - radius, 'left': x - radius, 'opacity': 0 }, 100, function () { jQuery(this).remove(); });
                        });

        //Display the click number
        numberOfClicks++;
        var clickBox = jQuery("<span class='clickBox' data-top='" + (y + scroll.top) + "' data-left='" + (x + scroll.left) + "'>" +
                                    numberOfClicks +
                                "</span>");
        clickBox.css('top', y).css('left', x);
        jQuery('#pageWrap').append(clickBox);
        clickBox.fadeIn(200);

        //Play click sound
        clickSound.currentTime = 0;
        clickSound.play();

        //Trigger click event in iframe
        oIframe.contentWindow.postMessage(JSON.stringify({ task: 'CLK' }), "*");
    }

    function triggerValueChange(sel, val, l, i) {
        if (val.length >= l) {
            //Change input value
            oIframe.contentWindow.postMessage(JSON.stringify({ task: 'VAL', sel: sel, val: val.slice(0, l) }), "*");
            setTimeout(function () { triggerValueChange(sel, val, l + 1, i); }, 60);
        }
        else {
            if (i + 1 < record.length) {
                playRecord(i + 1);
            }
            else {
                recordPlaying = false;
                jQuery('#recordControls button#play').text('Play');
                userTrackAjax.getNextRecord(options.lastid);
            }
        }
    }

    function iframeRealClick() {
        if (elementUnder !== null) {
            if (elementUnder.nodeName == 'SELECT')
                jQuery(elementUnder).get(0).setAttribute('size', elementUnder.options.length);
            else {
                var link = jQuery(elementUnder).parents('a').eq(0);
                if (link !== undefined) {
                    link = link.attr('href');
                    if (link !== undefined && (link.indexOf('//') != -1 || link.indexOf('www.') != -1) && link.indexOf(window.location.host) == -1)
                        link = 'external';
                }
                if (link != 'external')
                    fireEvent(elementUnder, 'click');
                else {
                    alertify.alert('User has left the website');
                }
            }
        }

        if (lastElement !== null && lastElement.nodeName == 'SELECT')
            jQuery(lastElement).get(0).setAttribute('size', 1);
        lastElement = elementUnder;

    }

    function scrollIframe(x, y) {
        //Save current scroll data
        scroll.left = x;
        scroll.top = y;

        //Scroll iframe to left:x, top:y
        oIframe.contentWindow.postMessage(JSON.stringify({ task: 'SCR', top: y, left: x }), "*");
    }

    function setRecordList(data) {

        // Cache selector
        var pageHistoryDiv = jQuery('#pagesHistory');
        
        // Clear old list
        pageHistoryDiv.html('');

        // Add each page to the list
        for (var v in data) {
            var page = data[v];
            var div = jQuery('<div></div>');

            // Save all the data as data
            div.attr('data-url', page.page);
            div.attr('data-resolution', page.res);
            div.attr('data-date', page.date);
            div.attr('data-id', page.id);

            // Set visible data
            div.text(page.page);
            div.attr('title', page.page);

            // Page has no data
            if(page.id == 0) {
                div.addClass('disabled');
                div.attr('title', 'User visited this page but left it before any data could be recorded');
            }

            pageHistoryDiv.append(div);
        }
    }

    return {
        startPlayback: startPlayback,
        setCurrent: setCurrentRecord,
        setNext: setNextRecord,
        prepare: prepareRecord,
        playFrom: playRecord,
        setRecordList: setRecordList,
        reset: resetElements,
        scrollTo: scrollIframe
    };

}());