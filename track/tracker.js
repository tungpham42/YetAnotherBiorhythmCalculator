/**
 * Tracker, to be included on the pages where user movement must be monitored.
 * Part of userTrack system.
 */

'use strict';

if(typeof UST !== 'undefined') {
    // eslint-disable-next-line
    alert("userTrack: [tracker.js] was included twice on this page. Please remove one instance.");
}

var UST = {
    DEBUG: false,
    settings: {
        isStatic: true,
        recordClick: true,
        recordMove: true,
        recordKeyboard: true,
        delay: 200,
        maxMoves: 800,
        serverPath: "//nhipsinhhoc.vn/track",
        percentangeRecorded: 100,
        ignoreGET: ['utm_source','utm_ccc_01','gclid','utm_campaign','utm_medium'],
        ignoreIPs: ['66.249.66.50','66.249.66.20','66.249.66.56','66.249.66.17','66.249.66.20','66.249.66.50','66.249.66.56','66.249.66.14'],
        minIdleTime: 2,
        disableMobileTracking: false
    }
};

UST.randomToken = function () {
    return Math.random().toString(36).substr(2) + Math.random().toString(36).substr(2);
};

UST.enableRecord = function() {
    localStorage.noRecord = 'false';
    return 'Recording of this device has been ENABLED.';
};

UST.disableRecord = function() {
    localStorage.noRecord = 'true';
    return 'Recording of this device has been DISABLED.';
};

UST.canRecord = function () {
    // If is mobile device
    UST.isMobileDevice = (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i).test(navigator.userAgent);
    if (UST.isMobileDevice && UST.settings.disableMobileTracking) {
        return false;
    }

    // If it is in iframe, don't record
    if (top !== self) {
        return false;
    }

    // If we don't want to be recorded
    if (localStorage.noRecord === 'true') {
        return false;
    }

    // If we only have to record a percentage of visitors
    // If the visitor was not already being recorded
    if(localStorage.getItem('token') === null) {
        if(Math.random() * 100 >= UST.settings.percentangeRecorded) {
            UST.disableRecord();
            return false;
        }
    }

    return true;
};

/**
 * Checks wehther the requirements for running the tracker are met.
 * 
 * @returns {String}
 */
UST.testRequirements = function () {
    // Was previously used to test for jQuery requirement
    return 'ok';
};

/**
 * Helper function to find the main content area.
 * @return {HTMLElement} - The the Element that probably contains the content.
 */
UST.getContentDiv = function() {

    var mostProbable = document.body;
    var maxP = 0;
    var documentWidth = mostProbable.scrollWidth;
    var documentHeight = mostProbable.scrollHeight;

    var divList = document.getElementsByTagName('div');
    Array.prototype.forEach.call(divList, function(el) {
        var probability = 0;
        var style = el.currentStyle || window.getComputedStyle(el);
        
        if (style.position === 'static' || style.position === 'relative')
            probability += 2;

        if (el.scrollHeight > documentHeight / 2)
            probability += 3;

        if (el.parentElement.nodeName === 'BODY')
            probability++;

        if (style.marginLeft === style.marginRight)
            probability++;

        if (el.getAttribute('id') === 'main')
            probability += 3;

        if (el.getAttribute('id') === 'content')
            probability += 2;

        if (el.getAttribute('id') === 'container')
            probability++;

        if (el.scrollWidth !== documentWidth)
            probability += 2;

        if (probability > maxP) {
            maxP = probability;
            mostProbable = el;
        }
    });

    return mostProbable;
};

// Get root url to website/ to server
UST.getContextPath = function() {
    return UST.settings.serverPath + '/';
};

// Get domain name, without www.
UST.getDomain = function() {
    // Always remove www. from the begining
    if (document.domain.indexOf('www.') === 0) {
        return document.domain.substr(4);
    }

    return document.domain;
};

UST.removeURLParam = function(key, url) {
    var rtn = url.split("?")[0],
        param,
        paramsArr = [],
        queryString = (url.indexOf("?") !== -1) ? url.split("?")[1] : "";
    if (queryString !== "") {
        paramsArr = queryString.split("&");
        for (var i = paramsArr.length - 1; i >= 0; i -= 1) {
            param = paramsArr[i].split("=")[0];
            if (param === key) {
                paramsArr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + paramsArr.join("&");
    }
    return rtn;
};

// Get the current URL and remove unwanted GET params
UST.getCleanPageURL = function() {
    var currentURL = window.location.pathname + window.location.search;

    // Only compute again if different from last URL
    if(UST.lastURL !== currentURL) {
        UST.lastURL = currentURL;

        UST.cleanPageURL = currentURL;
        for(var key in UST.settings.ignoreGET) {
            var param = UST.settings.ignoreGET[key];
            UST.cleanPageURL = UST.removeURLParam(param, UST.cleanPageURL);

            if(UST.cleanPageURL[UST.cleanPageURL.length - 1] === '?') {
                UST.cleanPageURL = UST.cleanPageURL.slice(0, -1);
            }
        }
    }

    return UST.cleanPageURL;
};

// https://github.com/Cristy94/dynamic-listener
/* global addDynamicEventListener:true */
// eslint-disable-next-line
!function(t){"use strict";function e(t,e){return function(o){o.target&&o.target.matches(t)&&e.apply(this,arguments)}}Element.prototype.matches||(Element.prototype.matches=Element.prototype.matchesSelector||Element.prototype.mozMatchesSelector||Element.prototype.msMatchesSelector||Element.prototype.oMatchesSelector||Element.prototype.webkitMatchesSelector||function(t){for(var e=(this.document||this.ownerDocument).querySelectorAll(t),o=e.length;--o>=0&&e.item(o)!==this;);return o>-1}),t.addDynamicEventListener=function(t,o,n,r,c){t.addEventListener(o,e(n,r),c)}}(this);
   

// Functions to store/load numbers as 4 digit numbers
UST.coord4 = {
    // Auxiliary function to prepend '0' to get a 4 digit number
    fillZeros: function(x) {
        x = x.toString();

        while (x.length < 4) {
            x = '0' + x;
        }

        return x;
    },

    // Auxiliary function to get a point from a string, space separated resolution
    get2DPoint: function(x) {
        
        x = x.toString();

        var p = {
            x: x.substring(0, 4),
            y: x.substring(4)
        };

        // Remove prepending 0s
        while (p.x[0] === '0') {
            p.x = p.x.substring(1);
        }

        while (p.y[0] === '0') {
            p.y = p.y.substring(1);
        }

        return p;
    }
};

// Create the function without initing so it doesn't throw undefined errors in playback mode
UST.addTag = function () { console.log('addTag was called before initializing the function.'); };

// Auxiliarry DOM functions
UST.DOM = {

    ready: function (fn) {
        if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading") fn();
        else document.addEventListener('DOMContentLoaded', fn);
    },

    postAjax: function (url, data, success) {
        var params = typeof data == 'string' ? data : Object.keys(data).map(
            function (k) { return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]); }
        ).join('&');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', url);
        xhr.onreadystatechange = function () {
            if (xhr.readyState > 3 && +xhr.status === 200) { success(xhr.responseText); }
        };
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(params);
        return xhr;
    },

    /**
     * Returns the position of the element relative to the document.
     * @param  {HTMLElement} el
     * @return {Object} The `top` and `left` offset.
     */
    offset: function(el) {
        var rect = el.getBoundingClientRect();

        return {
            top: rect.top + document.body.scrollTop | 0,
            left: rect.left + document.body.scrollLeft | 0
        };
    },

    /**
     * Returns the document scroll top position converted to an integer.
     */
    docScrollTop: function() {
        return (window.pageYOffset || document.documentElement.scrollTop) | 0;
    },

    /**
     * Returns the document scroll left position converted to an integer.
     */
    docScrollLeft: function() {
        return (window.pageXOffset || document.documentElement.scrollLeft) | 0;
    },

    windowWidth: function() {
        return Math.max(document.documentElement.clientWidth, window.innerWidth || 0) | 0;
    },

    windowHeight: function() {
        return (window.innerHeight || document.documentElement.clientHeight) | 0;
    },

    hasClass: function(elem, className) {
        return (" " + elem.className + " ").indexOf(" " + className + " ") > -1;
    },

    getClosest: function(elem, selector) {
        while (elem !== document.body && elem !== document.documentElement && elem) {
            if (elem.matches(selector)) return elem;
            elem = elem.parentElement;
        }
        return null;
    },

    // Get unqiue path to element
    getUniquePath: function (element) {
        //https://github.com/rishihahs/domtalk/blob/master/index.js
        if (element === document.documentElement) {
            return ':root';
        } else if (element.tagName && element.tagName.toUpperCase() === 'BODY') {
            return 'body';
        } else if (element.id) {
            return '#' + element.id;
        }

        var parent = element.parentNode;
        var parentLoc = UST.DOM.getUniquePath(parent);

        var children = parent.childNodes;

        var index = 0;
        for (var i = 0; i < children.length; i++) {
            // nodeType is 1 if ELEMENT_NODE
            // or if current element is textNode, also count previous textNodes
            if (children[i].nodeType === 1 || element.nodeType === 3) {
                if (children[i] === element) {
                    break;
                }

                index++;
            }
        }
        return parentLoc + '(' + (index + 1) + ')';
    },

    getSelection: function() {
        return window.getSelection();
    }
};

UST.init = function () {

    UST.DEBUG && console.log(localStorage);

    // Make sure all client-side requirements for userTrack are fulfilled
    var errorStarting = UST.testRequirements();
    if(errorStarting !== 'ok') {
        console.log('userTrack tracker could not be started.', errorStarting);
        return;
    }

    // If tracking is disabled for this device or globally or the user should not be tracked
    if (!UST.canRecord()) return;

    // Constants
    var VAL_SEPARATOR = '_';
    var EVT_SEPARATOR = '~';
    var KEY_SEPARATOR = "`";

    // Tagging
    UST.addTag = function(tag) {

        if(typeof tag === 'undefined' || tag.length === 0) {
            console.log("Tag cannot be empty!");
            return 0;
        }

        UST.DOM.postAjax(getContextPath() + 'addTag.php', {
            clientID: localStorage.getItem('clientID'),
            tagContent: tag
        }, function () {
            UST.DEBUG && console.log('Tag ' + tag + 'added');
        });

        return 1;
    };

    // Aliases
    var getContextPath = UST.getContextPath;
    var getDomain = UST.getDomain;

    // Store the last data sent index
    var partialLastIndex = -1;


    /**
     * Just join the Array objects.
     * The Array elements are already strings and compressed.
     *
     * Compressed data has this format:
     * eg.
     * {'t': 'm', 'x': 100, 'y': 500} becomes
     * m_100_500
     * 
     * @param {Array} arr - The array to compress.
     * @return {String}
     */
    UST.ArrayToCompressedData = function(arr) {
        // Empty data guard
        if(!arr || arr.length === 0) return '';

        // Separate events by ~
        // !TODO: Better separate events in order not to have collisions with user input.
        return arr.join(EVT_SEPARATOR);
    };

    /**
     * Calls recurseSend immediately and resets the recurseSendTimeout and sendDataDelay.
     * This might be used to force send all data on page unload or when a specific action happens.
     */
    UST.forceSendData = function() {
        UST.sendDataDelay = 300;
        clearTimeout(UST.recurseSendTimeout);
        recurseSend();
        return 'Data sending has been successfully queued!';
    };

    var hadPreviousSelection = true;
    UST.getSelectionData = function() {
        // Wait a bit for selection/deselection to complete
        setTimeout(function() {
            try {

                var selection = UST.DOM.getSelection();

                if(selection) {

                    if(selection.anchorNode === null) {
                        if (hadPreviousSelection) {
                            record.push('a' + KEY_SEPARATOR + '0');
                            hadPreviousSelection = false;
                        }
                        return;
                    }

                    // If it's a text node replace the last (1) with 1#T#
                    var startElPath = UST.DOM.getUniquePath(selection.anchorNode);
                    if(selection.anchorNode.nodeType === 3) startElPath = startElPath.replace(/\((\d+)\)$/g, ' $1#T#');
                    var endElPath = UST.DOM.getUniquePath(selection.focusNode);
                    if(selection.focusNode.nodeType === 3) endElPath = endElPath.replace(/\((\d+)\)$/g, ' $1#T#');
                    var selStart = selection.anchorOffset;
                    var selEnd = selection.focusOffset;

                    // Make sure end is always larger than start
                    if(selStart > selEnd) {
                        var t = selStart;
                        selStart = selEnd;
                        selEnd = t;

                        t = startElPath;
                        startElPath = endElPath;
                        endElPath = t;
                    }

                    hadPreviousSelection = true;
                    
                    record.push(
                        'a' + KEY_SEPARATOR +
                        encodeURIComponent(startElPath) +
                        VAL_SEPARATOR +
                        encodeURIComponent(endElPath) +
                        VAL_SEPARATOR +
                        encodeURIComponent(selStart) +
                        VAL_SEPARATOR +
                        encodeURIComponent(selEnd)
                    );
                }
            } catch(e) { console.log(e); }
        }, 10);
    };

    UST.sendData = function(clientPageID) {

        // Update the date of the client to keep the session active
        localStorage.setItem('lastTokenDate', new Date());

        // Send all data at once, fewer requests
        var data = {
            movements: '',
            clicks: '',
            partial: ''
        };

        /**
         * Add movements to data
         */
        var toSend = [];
        for (var v in movements) {
            var obj = UST.coord4.get2DPoint(v);
            obj.count = movements[v];
            toSend.push(obj.x + VAL_SEPARATOR + obj.y + VAL_SEPARATOR + obj.count);
        }

        if (toSend.length > 0) {
            data.movements = toSend.join(EVT_SEPARATOR);
            // Clear old movements
            movements = {};
        }

        /**
         * Add clicks to data
         */
        toSend = [];
        for (v in clicks) {
            var obj = UST.coord4.get2DPoint(v);
            obj.count = clicks[v];
            toSend.push(obj.x + VAL_SEPARATOR + obj.y + VAL_SEPARATOR + obj.count);
        }

        if (toSend.length > 0) {
            data.clicks = toSend.join(EVT_SEPARATOR);
            // Clear old clicks
            clicks = {};
        }


        /**
         * Add partial to data
         */
        var cachedRecords = localStorage.getItem('record');

        if (cachedRecords !== null) {
            // Get new data since last sent
            cachedRecords = JSON.parse(cachedRecords);
            data.partial = cachedRecords.slice(partialLastIndex + 1, cachedRecords.length);
            
            // Update last index sent
            partialLastIndex = cachedRecords.length - 1;
        }       

        /**
         * Send the data if we have new data
         */
        if(data.movements.length || data.clicks.length || data.partial.length) {
            UST.startDataTransferBatch(data, clientPageID);
        }

        // Data should have been sent, reset our activity
        activityCount = 0;
    };

    /**
     * Starts a request for the `addData.php` tracking pixel.
     * This adds heatmap data and partial session tracking data.
     * @param  {Object} data         The data to send and store on the server.
     * @param  {Number} clientPageID The ID of the current page visit of the current client.
     */
    UST.startDataTransferBatch = function(data, clientPageID) {
        UST.transferDataViaPixel([
            'm=' + data.movements, // movements, already in string format
            'c=' + data.clicks, // clicks, already in string format
            'p=' + UST.ArrayToCompressedData(data.partial) + EVT_SEPARATOR, // partial, is an Array
            'i=' + clientPageID // clientPageID
        ]);
    };

    UST.startFinalRecordTransfer = function(cachedRecords, clientPageID) {
        UST.transferDataViaPixel([
            'r=' + UST.ArrayToCompressedData(cachedRecords),
            'w=1',
            'i=' + clientPageID
        ]);
    };

    var trackingPixelURL = getContextPath() + '/tracker/addData.php';

    /**
     * Adds the given URL params to the tracking pixel src and sets the .src
     * @param  {Array} params A list of parameters to add in the form ['key=value',...]
     */
    UST.transferDataViaPixel = function(params) {
        // Create an image each time, we might have several requests happening at once
        var trackingPixelImg = new Image();

        // Remove empty params (their format is 'v=', length is 2)
        params = params.filter(function(el) {
            return el.length > 2;
        });

        trackingPixelImg.src = trackingPixelURL + '?' + params.join('&');
    };

    UST.partialToFinal = function() {

        // Send complete record stored in localStorage to server
        var cachedRecords = localStorage.getItem('record');
        var clientPageID = localStorage.getItem('clientPageID');
        localStorage.removeItem('record');

        UST.DEBUG && console.log('Trying to save final for clientPage #' + clientPageID, cachedRecords);

        if (cachedRecords !== null) {
            UST.startFinalRecordTransfer(JSON.parse(cachedRecords), clientPageID);
        }
    };

    // Save last page recording as final.
    UST.partialToFinal();

    // Client token, if 40s have passed create a new token
    var lastTokenDate = localStorage.getItem('lastTokenDate');
    if (localStorage.getItem('token') === null || (new Date() - Date.parse(lastTokenDate) > 40000)) {
        // New token
        localStorage.setItem('token', UST.randomToken());

        // Erase old clientID
        localStorage.removeItem('clientID');
    }

    var token = localStorage.getItem('token');
    localStorage.setItem('lastTokenDate', new Date());

    // Make sure tab is focused while recording
    // @TODO: Record window blur/focus events instead.
    // var focused = true;
    // (document).hover(function () { focused = true; },
    //                       function () { focused = false; });

    /*** Tracker ***/
    var lastDate = new Date();
    var lastActionDate = new Date();
    var scrollTimeout = null;
    var maxTimeout = 3000;
    var movements = {};
    var clicks = {};
    var record = [];
    var activityCount = 0;

    // Last movement coordinates, relX -> static anchor
    var lastX, lastY;
    var relX = 0;

    // If wordpress admin bar is visible, offset all postions vertically
    var offsetY = 0;
    var wpAdminBar = document.getElementById('wpadminbar');
    if(wpAdminBar) {
        offsetY = -wpAdminBar.offsetHeight;
    }

    var cachedClicks = localStorage.getItem('clicks');
    if (cachedClicks !== null && cachedClicks !== undefined) {
        clicks = JSON.parse(cachedClicks);
        UST.sendData(localStorage.getItem('clientPageID'));
    }

    // Get clientPageID (based on token and current page info)
    var clientPageID;
    var clientID = localStorage.getItem('clientID');

    UST.DOM.postAjax(getContextPath() + 'tracker/createClient.php',
        {
            // Resolution
            r: UST.DOM.windowWidth() + ' ' + UST.DOM.windowHeight(),
            // Token
            t: token,
            // URL
            u: UST.getCleanPageURL(),
            // Domain
            d: getDomain(),
            // clientID
            c: clientID,
            // Referrer
            s: document.referrer,
            // Device type
            v: UST.isMobileDevice === true ? 1 : 0
        },
        function (data) {
            UST.DEBUG && console.log(data);
            if(data) data = JSON.parse(data);

            // Save current page session ID
            clientPageID = data.clientPageID;

            // Cache clientPageID for sending full recording on next page load
            localStorage.setItem('clientPageID', clientPageID);

            // Also cache client id
            localStorage.setItem('clientID', data.clientID);

            startSendingData();
        });


    // Clear partial data
    UST.DOM.postAjax(getContextPath() + 'helpers/clearPartial.php',
        {
            clientPageID: localStorage.getItem('clientPageID')

        }, function () {
            UST.DEBUG && console.log('partials cleared');
        },
        function (data) {
            console.log("Could not clear partial!" + data);
        });

    if (UST.settings.isStatic) {
        relX = UST.DOM.offset(UST.getContentDiv()).left;
    }

    // Tag triggers
    addDynamicEventListener(document.body, 'click', '[data-ust_click_tag]', function(e) {
        if(!e.target) return;
        var tag = e.target.dataset.ust_click_tag;
        UST.addTag(tag);
    });

    // Helper function to record delay
    function addIdleTime(curDate, interpTime) {
        var idleTime = curDate - lastActionDate;
        if(typeof interpTime === 'undefined') interpTime = 0;

        if(idleTime >= UST.settings.minIdleTime) {

            idleTime -= interpTime;
            if(idleTime >= UST.settings.minIdleTime) {
                record.push('i' + KEY_SEPARATOR + idleTime);
            }
        }

        lastActionDate = curDate;
    }

    function handleClickEvent(e, isRightClick) {
        // Ignore artificial clicks
        if(typeof e.pageX === 'undefined' || !e.target) {
            return;
        }

        var clickPos = {
            x: e.pageX | 0,
            y: e.pageY | 0
        };

        // If click was dynamically triggered, get the center of the target
        if(e.pageX === e.pageY && e.pageX === 0) {
            var offset = UST.DOM.offset(e.target);

            clickPos.x = offset.left + (e.target.clientWidth / 2) | 0;
            clickPos.y = offset.top + (e.target.clientHeight / 2) | 0;
        }
        
        if (UST.settings.recordClick) {
            var p = UST.coord4.fillZeros(clickPos.x - relX).toString() + UST.coord4.fillZeros(clickPos.y + offsetY);
            
            if (clicks[p] === undefined) {
                clicks[p] = 0;
            }

            clicks[p]++;
        }
    
        // Record time since last action    
        addIdleTime(new Date());
        
        // Save the data into localStorage
        var clickData = 'c' + KEY_SEPARATOR + clickPos.x + VAL_SEPARATOR + (clickPos.y + offsetY);
        if(isRightClick) {
            clickData += VAL_SEPARATOR + 'R';
        }
        record.push(clickData);
        localStorage.setItem('record', JSON.stringify(record));
        localStorage.setItem('url', UST.getCleanPageURL());
        activityCount += 10;

        if (UST.DOM.getClosest(e.target, 'a') !== null) {
            localStorage.setItem('clicks', JSON.stringify(clicks));
            localStorage.setItem('url', UST.getCleanPageURL());
        }
    }

    // Record clicks
    document.addEventListener('click', handleClickEvent);
    document.addEventListener('keyup', UST.getSelectionData);
    document.addEventListener('mouseup', UST.getSelectionData);
    document.addEventListener('contextmenu', function(e) {
        handleClickEvent(e, true);
    });

    // Record resize events
    var resizeTimeout;
    window.addEventListener('resize', function() {
        // Debounce, once every 150ms
        if(!resizeTimeout) {
            resizeTimeout = setTimeout(function() {
                resizeTimeout = null;
                addCurrentWindowSize();
            }, 150);
        }
    }, true);

    function addCurrentWindowSize() {
        record.push(
            'r' + KEY_SEPARATOR + 
            UST.DOM.windowWidth() +
            VAL_SEPARATOR +
            UST.DOM.windowHeight()
        );
    }

    // Record scroll events, use passive event
    var supportsPassive = false;
    try {
        var opts = Object.defineProperty({}, 'passive', {
            get: function () {
                supportsPassive = true;
            }
        });
        window.addEventListener("test", null, opts);
    } catch (e) { } // eslint-disable-line
    var lastScrollDate = undefined;
    window.addEventListener('scroll', function () {
        
        var now = new Date();
        
        // Save scroll if 100ms have passed while scrolling
        if(lastScrollDate === undefined || now - lastScrollDate >= 100) {
            UST.DEBUG && console.log('Scroll event recorded!');
            lastScrollDate = now;
            addIdleTime(now, 100);
            record.push(
                's' + KEY_SEPARATOR +
                UST.DOM.docScrollLeft() +
                VAL_SEPARATOR + 
                UST.DOM.docScrollTop()
            );
            localStorage.setItem('record', JSON.stringify(record));
            activityCount++;
        }

        // Also save scroll 100ms after the last scroll event (so the final position is set)
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function () {
            UST.DEBUG && console.log('Scroll event recorded!');
            addIdleTime(now, 100);
            record.push(
                's' + KEY_SEPARATOR +
                UST.DOM.docScrollLeft() +
                VAL_SEPARATOR + 
                UST.DOM.docScrollTop()
            );
            localStorage.setItem('record', JSON.stringify(record));
            lastScrollDate = new Date();
            activityCount++;
        }, 100);
    }, supportsPassive ? { passive: true } : false);

    // Record mouse movements
    document.addEventListener('mousemove', function (e) {
        // Invalid or artificial movement triggered
        if(typeof e.pageX === 'undefined') {
            return;
        }

        var curDate = new Date();
        var passed = curDate - lastDate;
        if (passed < UST.settings.delay)
            return;

        // A new mousemove should be recorded, record idle time prior to this movement
        addIdleTime(curDate, UST.settings.delay);

        if (--UST.settings.maxMoves > 0 && passed < maxTimeout) {
            if (lastX !== undefined && UST.settings.recordMove) {
                var p = UST.coord4.fillZeros(lastX).toString() + UST.coord4.fillZeros(lastY);

                // Also filter some possible invalid movements
                if (!(lastX === 0 || lastY === 0)) {
                    if (movements[p] === undefined)
                        movements[p] = 0;
                    movements[p]++;
                }
            }

            if (!(lastX === 0 || lastY === 0)) {
                record.push('m' + KEY_SEPARATOR + (e.pageX | 0) + VAL_SEPARATOR + (e.pageY + offsetY | 0));
                localStorage.setItem('record', JSON.stringify(record));
                activityCount++;
            }
        }

        lastDate = curDate;
        lastX = e.pageX;
        lastY = e.pageY + offsetY;
        if (UST.settings.isStatic) {
            lastX -= relX;
        }
    });

    /* Record keyboard input */
    if (UST.settings.recordKeyboard) {
        addDynamicEventListener(document.body, 'focusout', 'input:not([type="submit"]):not([type="button"])', function (e) {
            if(!e.target) return;
            var el = e.target;

            //Don't record val change on password inputs or 
            //  elements with class 'noRecord'
            if (UST.DOM.hasClass(el, 'noRecord') || el.type === 'password')
                return;

            // Record time since last action    
            addIdleTime(new Date());

            // Add unique selector to the blured element
            var uniquePath = UST.DOM.getUniquePath(el);
            record.push(
                'b' + KEY_SEPARATOR +
                encodeURIComponent(uniquePath) +
                VAL_SEPARATOR +
                encodeURIComponent(el.value)
            );
            localStorage.setItem('record', JSON.stringify(record));
        });
    }

    // Record select change
    addDynamicEventListener(document, 'change', 'select', function (e) {
        if(!e.target) return;
        var el = e.target;

        if (UST.DOM.hasClass(el, 'noRecord'))
            return;

        var uniquePath = UST.DOM.getUniquePath(el);
        record.push(
            'b' + KEY_SEPARATOR +
            encodeURIComponent(uniquePath) +
            VAL_SEPARATOR +
            encodeURIComponent(el.value)
        );
        localStorage.setItem('record', JSON.stringify(record));
    });

    // Start sending data after clientPageID is received
    function startSendingData () {
        // Send data to server once in a while, exponential
        UST.sendDataDelay = 300;
        recurseSend();
    }

    function recurseSend() {
        UST.DEBUG && console.log("Sending data for clientPageID: ", clientPageID);
        
        // Max delay between data sending is 4seconds
        if (UST.sendDataDelay < 4000)
            UST.sendDataDelay += 400;

        // Reduce delay if activity happened soon
        if(UST.sendDataDelay > 2000 && localStorage.getItem('record') && activityCount > 10) {
            UST.sendDataDelay = 800;
        }

        UST.sendData(clientPageID);
        UST.recurseSendTimeout = setTimeout(recurseSend, UST.sendDataDelay);
    }
};

// Display the error message if userTrack can not start for some reason
var errorMessage = UST.testRequirements();
if(errorMessage !== 'ok') {
    console.log(errorMessage);
}

// DOM ready event, main entry function
UST.DOM.ready(function () {
    // Check if we have to get the user IP
    if(UST.canRecord && UST.settings.ignoreIPs && UST.settings.ignoreIPs.length > 0
        && UST.settings.ignoreIPs[0] !== '') {

        // Adding the script tag to the head as suggested before
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = UST.getContextPath() + 'helpers/getIP.php';

        var initCalled = false;

        // Then bind the event to the callback function.
        // There are several events for cross browser compatibility.
        script.onreadystatechange = script.onload = function() {
            // Make sure event is called only once
            if(initCalled) return;
            initCalled = true;

            // If this IP is not ignored start tracking
            /* global ust_myIP */
            if(UST.settings.ignoreIPs.indexOf(ust_myIP) === -1) {
                UST.init();
            } else {
                UST.disableRecord();
            }
        };

        // Fire the loading
        head.appendChild(script);
    } else {
        UST.init();
    }
});

/**
 * Playback Functions
 *
 * Used if frame is insider userTrack admin panel
 */
if (top !== self) {
    (function() {
        var head = document.getElementsByTagName('head')[0];

        if(typeof jQuery === 'undefined') {
            var jqScriptTag = document.createElement('script');
            jqScriptTag.type = 'text/javascript';
            jqScriptTag.async = false;
            jqScriptTag.src = UST.getContextPath() + 'lib/jquery.1.12.4.min.js';
            head.appendChild(jqScriptTag);
        }


        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.async = false;
        script.src = UST.getContextPath() + 'tracker/inject.js';
        head.appendChild(script);
    })();
}
