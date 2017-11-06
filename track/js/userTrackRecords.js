/* global DEBUG, options, iframeFit, setIframeSource,
          record:true, progressBar, settings, clickSound */

(function ($) {
    'use strict';

    var UST = window.UST ? window.UST : window.UST = {};

    /***
     Manage record playback
    ***/
    UST.Records = (function () {

        // Private variables
        var scroll = { left: 0, top: 0 };
        var cursor = $('<img id="cursor" src="images/cursor.png"/>');
        var numberOfClicks = 0;
        var lastPlayedIndex = 0;
        var $playButton = $('#recordControls button#play');
        var $pagesHistory = $('#pagesHistory');
        var noDataLoadTries = 0;
        var noNextPageRetries = 0;

        // Constants
        var MAX_EXTRA_DATA_TRIES = 10;
        var MAX_NEXT_PAGE_RETRIES = 10;

        // Initialization
        $(function () {
            $('body').append(cursor);
        });

        // Prepare and play the record given by the arguments
        function prepareRecord(id, page, res, clientpageid) {

            if (+id === 0) {
                alertify.alert('Selected record does not contain any data.');
                return;
            }

            DEBUG && console.log('Prepare record: ', id, page, res);

            options.resolution = res;
            options.recordid = id;
            options.clientpageid = clientpageid;
            options.url = page;
            options.stopLoadEvents = true;

            // Set iframe size
            var res = options.resolution.split(' ');
            iframeFit(res[0], res[1]);

            // Make sure that the iframe scale was not changed
            UST.Heatmap.setScale(1);

            // Set iframe path
            var absolutePath = '';
            if (options.domain !== '')
                absolutePath = '//www.' + options.domain;
            setIframeSource(absolutePath + options.url);

            UST.API.getRecord(options.clientpageid).then(setCurrentRecord);

            $('#recordList').fadeOut(300);
            $('#recordControls button').attr('disabled', false);
        }

        function continuePlayingFromIndex(i) {
            UST.recordPlaying = true;
            $playButton.text('| |');
            playRecord(i);
        }

        function playRecordFromHistoryAtIndex(index) {
            // This record has ended but we have other records ready
            DEBUG && console.log('A new page has been found while trying to get new data.');
            $('div:not(.disabled)', $pagesHistory).eq(index).trigger('click');
        }

        // Adds more data to the data stored for this page
        function getExtraRecordData() {
            DEBUG && console.log('Searching new recorded data for this user.');
            clearTimeout(UST.liveGetExtraDataTimeout);

            // If we already have new pages loaded, this page will have no new data
            var $pagesInHistory = $('div:not(.disabled)');
            var activeIndex = $('.active', $pagesHistory).index($('div:not(.disabled)'));
            if (activeIndex !== -1 && activeIndex < $pagesInHistory.length - 1) {
                DEBUG && console.log('New pages exist, no need to get new data for this one.');

                // If we finished playing the current recording, go to the next one
                if (!UST.recordPlaying) {
                    console.log('Record is not playing, going to the next available one');
                    playRecordFromHistoryAtIndex(activeIndex + 1);
                    UST.liveGetExtraDataTimeout = setTimeout(getExtraRecordData, UST.liveDataLoadDelay);
                }
                return;
            }

            if (options.liveSelected) {
                UST.API.getRecord(options.clientpageid).then((data) => {
                    var lastRecordLength = record.length;
                    record = uncompress(data);

                    // We have new data
                    if (lastRecordLength !== record.length) {
                        noDataLoadTries = 0;
                        // If current record reached end and stopped playing, start it again
                        if (lastPlayedIndex < record.length && !UST.recordPlaying) {
                            continuePlayingFromIndex(lastPlayedIndex + 1);
                        }
                    } else {
                        noDataLoadTries++;

                        if (noDataLoadTries > MAX_EXTRA_DATA_TRIES) {
                            if (!UST.recordPlaying) {
                                onPlaybackEnd();
                            }
                            return;
                        }
                    }
                    UST.liveGetExtraDataTimeout = setTimeout(getExtraRecordData, UST.liveDataLoadDelay);
                });
            }
        }

        function setNextRecord(data) {
            DEBUG && console.log('Set next record: ', data);

            // If there is a next record
            if (data.id !== 0) {
                prepareRecord(data.id, data.page, data.res, data.clientpageid);
            } else if (options.liveSelected) {
                UST.liveGetExtraDataTimeout = setTimeout(getExtraRecordData, UST.liveDataLoadDelay);
            } else {
                onPlaybackEnd();
            }
        }

        function updatePagesHistory() {
            if (!options.currentClientID) return;
            clearTimeout(UST.updatePagesHistoryTimeout);

            // @TODO: Maybe get pages history more often
            UST.API.getRecordList(options.currentClientID).then((data) => {
                // No more new data to show
                if (data.length === options.currentRecordList.length) {
                    noNextPageRetries++;

                    if (noNextPageRetries > MAX_NEXT_PAGE_RETRIES) {
                        DEBUG && console.log('No more new pages were found for this user.');
                        return;
                    }
                } else {
                    noNextPageRetries = 0;
                }

                // Update the history anyway, as last record might now have data and become enabled
                setRecordsList(data);

                UST.updatePagesHistoryTimeout = setTimeout(updatePagesHistory, UST.updatePagesHistoryDelay);
            });
        }

        /**
         * This function is called after all the records (pages) for 
         * the current client have been played.
         */
        function onPlaybackEnd() {
            if (options.autoplayMode) {
                if (options.autoplayList.length) {
                    // Get and remove the record
                    let rec = options.autoplayList.shift();
                    prepareRecord(rec.id, rec.page, rec.res, rec.clientpageid);
                    // Update the user info UI
                    UST.UI.setRecordInfo(rec.button);
                    return;
                }

            }

            // Else exit playback mode
            inPlaybackMode = false; // eslint-disable-line no-undef

            if (options.autoplayMode) {
                alertify.alert('Autoplay has finished playing all the selected sessions.');
                options.autoplayMode = false;
            } else {
                alertify.alert('User has left the website.');
            }
        }

        /**
         * Skips the current page playback to the next page recording.
         * Assumes current clientpageid is stored in `options.clientpageid`.
         */
        function goToNextRecord() {
            DEBUG && console.log('Getting next record after clientpageid #', options.clientpageid);
            UST.API.getNextRecord(options.clientpageid).then(recordData => {
                DEBUG && console.log("getNextRecord", recordData);

                recordData.id = Number(recordData.id);
                recordData.clientpageid = Number(recordData.clientpageid);
                setNextRecord(recordData);
            });
        }

        /**
         * Called after the record playback for the current page has finished. 
         */
        function onRecordPageEnd() {
            DEBUG && console.log('onRecordPageEnd called');
            UST.recordPlaying = false;
            $playButton.text('►');
            goToNextRecord();
        }

        function uncompress(data) {
            if (!data) return [];

            // Split each event
            data = data.split(UST.DATA_EVT_SEPARATOR);

            return data.map((el) => {
                // Invalid data is always skipped
                el = el.split(UST.DATA_KEY_SEPARATOR);

                if (el.length !== 2) return null;

                var type = el[0];
                var vals = el[1];

                var eventData = {};
                switch (type) {
                    // Inactivity
                    case 'i':
                        eventData.d = +vals;
                        break;
                    // Clicks
                    case 'c':
                        vals = vals.split(UST.DATA_VAL_SEPARATOR);
                        eventData.x = +vals[0];
                        eventData.y = +vals[1];
                        eventData.r = vals.length === 3; // If this is right click
                        break;
                    // Input blur
                    case 'b':
                        vals = vals.split(UST.DATA_VAL_SEPARATOR);
                        eventData.p = decodeURIComponent(vals[0]);
                        eventData.v = decodeURIComponent(vals[1]);
                        break;
                    // Text selection
                    case 'a':
                        // Handle text deselection
                        if (vals === '0') {
                            eventData.startElPath = null;
                        } else {
                            vals = vals.split(UST.DATA_VAL_SEPARATOR);
                            eventData.startElPath = decodeURIComponent(vals[0]);
                            eventData.endElPath = decodeURIComponent(vals[1]);
                            eventData.start = +decodeURIComponent(vals[2]);
                            eventData.end = +decodeURIComponent(vals[3]);
                        }
                        break;
                    // Movements, scroll
                    default:
                        vals = vals.split(UST.DATA_VAL_SEPARATOR);
                        eventData.x = +vals[0];
                        eventData.y = +vals[1];
                        break;
                }

                return Object.assign({ t: type }, eventData);
            });
        }

        function setCurrentRecord(data) {
            DEBUG && console.log('Set current record called');

            // If it is partial, remove last extra event separator
            if (data[data.length - 1] === UST.DATA_EVT_SEPARATOR) {
                data = data.slice(0, -1);
            }

            record = uncompress(data);

            setTimeout(function () {
                $('#play').trigger('click');

                highlightCurrentRecord();

                // Continously get data for live mode
                if (options.liveSelected) {
                    // Reset load data count for this page
                    noDataLoadTries = 0;
                    UST.liveGetExtraDataTimeout = setTimeout(getExtraRecordData, UST.liveDataLoadDelay);
                    UST.updatePagesHistoryTimeout = setTimeout(updatePagesHistory, UST.updatePagesHistoryDelay);
                } else {
                    clearTimeout(UST.liveGetExtraDataTimeout);
                    clearTimeout(UST.updatePagesHistoryTimeout);
                }
            }, 500);
        }

        function highlightCurrentRecord() {
            DEBUG && console.log('Current recording should be higlighted', options.clientpageid);

            // Highlight current page
            $('div', $pagesHistory).removeClass('active');
            $('div[data-clientpageid=' + options.clientpageid + ']', $pagesHistory).addClass('active');
        }

        function resetElements() {
            scroll = { left: 0, top: 0 };
            numberOfClicks = 0;
            noDataLoadTries = 0;
            noNextPageRetries = 0;
            lastPlayedIndex = 0;
            $('.clickBox').remove();
        }

        var lastP = {};

        /**
         * Calls playRecord after the given delay. 
         * 
         * @param {number} i 
         * @param {number} [delay=0] 
         */
        function playRecordAsync(i, delay = 0) {
            setTimeout(() => playRecord(i), delay);
        }

        // Play i-th action from recording
        function playRecord(i) {

            // If we stopped playback
            if (!UST.recordPlaying) return;

            // Make sure the event index is in range
            if (i >= record.length || !record[i]) {
                onRecordPageEnd();
                return;
            }

            lastPlayedIndex = i;

            // If it's the first event reset elements so that playing back multiple times
            //  yields same results
            if (i === 0) resetElements();

            // Get current event
            var eventData = JSON.parse(JSON.stringify(record[i]));
            if (eventData === undefined) {
                UST.recordPlaying = false;
                $playButton.text('►');
                return;
            }
            progressBar.css({ width: Math.round((i + 1) * 100 / record.length) + '%' });

            DEBUG && console.log(eventData);

            // Handle idle time
            if (eventData.t === 'i') {
                let delay = 0;
                if (localStorage.skipPauses !== "true") {
                    delay = eventData.d;
                }

                playRecordAsync(i + 1, delay);
                return;
            }

            // Window resize
            if (eventData.t === 'r') {
                // Send the resize event
                iframeFit(eventData.x, eventData.y);

                // Play the next event
                playRecordAsync(i + 1, 0);
                return;
            }

            eventData.x -= scroll.left;
            eventData.y -= scroll.top;

            // If click triggered an artificial mouse movement, skip it
            var oldP = record[i - 1];
            if (i > 0 && eventData.t === 'm' && oldP.t === 'c' && lastP.x === eventData.x && lastP.y === eventData.y) {
                // Play the next event
                playRecordAsync(i + 1, 0);
                return;
            }

            // Set element under
            UST.iframe.contentWindow.postMessage(JSON.stringify({ task: 'EL', x: eventData.x, y: eventData.y }), "*");

            // Trigger hover
            UST.iframe.contentWindow.postMessage(JSON.stringify({ task: 'HOV' }), "*");

            // Scroll
            if (eventData.t === 's') {
                scrollIframe(eventData.x + scroll.left, eventData.y + scroll.top);
                // Play the next event after 100ms delay
                playRecordAsync(i + 1, 100);
                return;
            }

            // Text selection
            if (eventData.t === 'a') {
                setSelection(eventData.startElPath, eventData.endElPath, eventData.start, eventData.end);
            }

            // Movement, click or input blur
            var interpTime = settings.delay - 20;
            if (eventData.t === 'c' && lastP.x === eventData.x && lastP.y === eventData.y) interpTime = 5;
            cursor.animate({
                top: eventData.y + $('#heatmapIframe').offset().top,
                left: eventData.x + $('#heatmapIframe').offset().left
            }, interpTime, function () {
                lastP.x = eventData.x;
                lastP.y = eventData.y;
                if (eventData.t === 'c')
                    triggerClick(eventData.x, eventData.y, eventData.r);
                if (eventData.t === 'b') {
                    triggerValueChange(eventData.p, eventData.v, 0, i);
                    return;
                }

                // Skip to the clicked time on the progress bar
                if (UST.skipToPercentage !== undefined) {
                    i = UST.skipToPercentage / 100 * record.length | 0;
                    UST.skipToPercentage = undefined;
                }

                playRecordAsync(i + 1, 0);
            });
        }

        function triggerClick(x, y, isRightClick) {

            // Absolute position coordinates
            x += $('#heatmapIframe').offset().left;

            // Display the click radius animation
            var circle = $("<div class='clickRadius'>&nbsp;</div>");
            var radius = 30;
            circle.css('top', y).css('left', x);
            $('#pageWrap').append(circle);
            circle.animate({
                height: radius,
                width: radius,
                top: y - (radius / 2),
                left: x - (radius / 2),
                opacity: 0.3
            }, 500, function () {
                circle.animate({
                    height: 2 * radius,
                    width: 2 * radius,
                    top: y - radius,
                    left: x - radius,
                    opacity: 0
                }, 100, function () { $(this).remove(); });
            });

            // Display the click number
            numberOfClicks++;
            var clickBox = $("<span class='clickBox' data-top='" + (y + scroll.top) + "' data-left='" + (x + scroll.left) + "'>" +
                numberOfClicks +
                "</span>");
            if (isRightClick) clickBox.addClass('rightClick');
            clickBox.css('top', y).css('left', x);
            $('#pageWrap').append(clickBox);
            clickBox.fadeIn(200);

            // Play click sound
            clickSound.currentTime = 0;
            clickSound.play();

            // Trigger click event in iframe
            UST.iframe.contentWindow.postMessage(JSON.stringify({ task: 'CLK' }), "*");
        }

        /**
         * Completes an input value, character by character.
         * One character every 60ms.
         * 
         * @param {String} sel The selector used to get the input element.
         * @param {Number} val The value to input.
         * @param {Number} l The index of the character.
         * @param {Number} i The index of the eventData. 
         *                   We need this to continue playback after input complete.  
         */
        function triggerValueChange(sel, val, l, i) {
            if (val.length >= l) {
                // Change input value
                UST.iframe.contentWindow.postMessage(JSON.stringify({ task: 'VAL', sel: sel, val: val.slice(0, l) }), "*");
                setTimeout(function () { triggerValueChange(sel, val, l + 1, i); }, 60);
            }
            else playRecordAsync(i + 1, 0);
        }

        /**
         * Scrolls the iframe window to the given X,Y coordinates.
         * 
         * @param {Number} x 
         * @param {Number} y 
         */
        function scrollIframe(x, y) {
            // Save current scroll data
            scroll.left = x;
            scroll.top = y;

            // Scroll iframe to left:x, top:y
            UST.iframe.contentWindow.postMessage(JSON.stringify({ task: 'SCR', top: y, left: x }), "*");
        }

        /**
         * Sets the current selection on the given element between start and end.
         * 
         * @param {string} startElPath 
         * @param {string} endElPath 
         * @param {number} start 
         * @param {number} end 
         */
        function setSelection(startElPath, endElPath, start, end) {
            UST.iframe.contentWindow.postMessage(JSON.stringify({
                task: 'SEL',
                startElPath: startElPath,
                endElPath: endElPath,
                start: start,
                end: end
            }), "*");
        }

        /**
         * Sets the pages history in the navigation bar.
         *  
         * @param {Array} data Array containing the info about each page visited. 
         */
        function setRecordsList(data) {

            options.currentRecordList = data;

            // Cache selector
            var $pagesHistory = $('#pagesHistory');
            var $wrap = $('<div>');

            // Add each page to the list
            for (var v in data) {
                var page = data[v];
                var div = $('<div></div>');

                // Save all the data as data
                div.attr('data-url', page.page);
                div.attr('data-resolution', page.res);
                div.attr('data-date', page.date);
                div.attr('data-id', page.id);
                div.attr('data-clientpageid', page.clientpageid);

                // Set visible data
                div.text(page.page);
                div.attr('title', page.page);

                // Page has no data
                if (+page.id === 0) {
                    div.addClass('disabled');
                    div.attr('title', 'User visited this page but left it before any data could be recorded');
                }

                $wrap.append(div);
            }

            // Update pages history list
            $pagesHistory.html($wrap.contents());
            highlightCurrentRecord();
        }

        return {
            setCurrent: setCurrentRecord,
            goToNextRecord: goToNextRecord,
            prepare: prepareRecord,
            playFrom: playRecord,
            setRecordsList: setRecordsList,
            reset: resetElements,
            scrollTo: scrollIframe
        };
    }());
})(jQuery);
