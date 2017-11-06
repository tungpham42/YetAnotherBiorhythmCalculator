/* global UST, html2canvas */
/**
 * This file is injected into the iframe to get access to the contentWindow.
 * This is done only when the site is viewed in the userTrack dashboard.
 */
(function () {
    var elementUnder = null;
    var lastElement = null;
    var lastEvent = null;

    /**
     * Hides the WP admin bar and removes the html
     */
    UST.removeWpAdminBar = function() {
        var bar = document.getElementById('wpadminbar');
        if(bar === null) return;

        // Remove margin
        document.documentElement.style.setProperty('margin-top', '0', 'important');
        // Hide the bar
        bar.style.display = 'none';
    };

    // Stop paint flashing when scrolling iframe
    document.documentElement.style.backfaceVisibility = 'hidden';

    // Hide the WP admin bar, make sure it was loaded
    setTimeout(UST.removeWpAdminBar, 300);

    // Decompress the compressed (i) with > *:nth-child(i);
    function uncompressSelector(selector) {
        return selector.replace(/\((\d+)\)/g, ' > *:nth-child($1)');
    }

    /**
     * Text selection might return a Text Node.
     * As it can not be selected by querySelector we use the #T# flag
     * And immediately before it we have the index of the node in its parent's children list.
     * 
     * eg. #content .blah 31#T# - Means get the 31st node of .blah children list
     * @param {any} selector 
     */
    function getElementNodeIncludingTextNode(selector) {
        var selector = uncompressSelector(selector);

        var node = null;
        if(selector.indexOf('#T#') !== -1) {
            var sel = selector.replace('#T#', '');
            var lastSpace = sel.lastIndexOf(' ');
            var textNodeIndex = +sel.slice(lastSpace);
            sel = sel.slice(0, lastSpace);

            node = document.querySelector(sel);
            // Get the nextNode element, 0 indexed
            node = node.childNodes.item(textNodeIndex - 1);
        } else {
            node = document.querySelector(selector);
        }

        return node;
    }

    // Allow cross-domain interactions using HTML5 postMessage
    var receiver = function (event) {

        // Don't filter requests by domain, no security problems
        // eslint-disable-next-line
        if (event.origin === UST.settings.serverPath || true) {
            // Check if JSON
            if (event.data[0] === '!' || (event.data[0] > 'A' && event.data[0] < 'z'))
                return;

            var data = JSON.parse(event.data);

            if (data.task !== undefined)
                lastEvent = event;

            switch (data.task) {
                // Allow hover events to be triggered, alter stylesheet
                case 'CSS':
                    // Also remove the admin bar when the stylsheet update is called
                    UST.removeWpAdminBar();
                    
                    for (var i = 0; ; ++i) {
                        var classes = document.styleSheets[i];
                        if (classes === undefined || classes === null)
                            break;
                        classes = classes.rules;

                        if (classes === undefined || classes === null)
                            continue;

                        for (var x = 0; x < classes.length; x++) {
                            if (classes[x].selectorText !== undefined) {
                                classes[x].selectorText = classes[x].selectorText.replace(':hover', '.hover');
                            }
                        }
                    }
                    break;

                // Set element under
                case 'EL':
                    elementUnder = document.elementFromPoint(data.x, data.y);
                    break;

                // Trigger hover
                case 'HOV':
                    iframeHover();
                    break;

                // Trigger click
                case 'CLK':
                    iframeRealClick();
                    break;
                
                // Update input value
                case 'VAL':
                    jQuery(uncompressSelector(data.sel)).trigger('focus').val(data.val);
                    break;
                
                // Return iframe size
                case 'SZ':
                    event.source.postMessage(JSON.stringify({
                        task: 'SZ',
                        w: Math.max(jQuery(document).width(), jQuery('html').width(), window.innerWidth),
                        h: Math.max(jQuery(document).height(), jQuery('html').height(), window.innerHeight)
                    }), event.origin);
                    break;

                // Scroll iframe
                case 'SCR':
                    jQuery(document).scrollTop(data.top);
                    jQuery(document).scrollLeft(data.left);
                    break;
                
                // Set selection
                case 'SEL':
                    window.getSelection().removeAllRanges();

                    if(data.startElPath !== null) {
                        var range = document.createRange();

                        // Start
                        var startEl = getElementNodeIncludingTextNode(data.startElPath);
                        range.setStart(startEl, data.start);

                        // End
                        var endEl = getElementNodeIncludingTextNode(data.endElPath);
                        range.setEnd(endEl, data.end);

                        window.getSelection().addRange(range);
                    }
                    break;

                // X position of first div
                case 'STATIC':
                    event.source.postMessage(JSON.stringify({ task: 'STATIC', X: UST.DOM.offset(UST.getContentDiv()).left }), event.origin);
                    break;

                // Return current iframe path
                case 'PTH':
                    event.source.postMessage(JSON.stringify({ task: 'PTH', p: location.pathname }), event.origin);
                    break;

                // Append the html2canvas library
                case 'addHtml2canvas':
                    if(typeof window.html2canvasAdded === "undefined") {
                        window.html2canvasAdded = true;
                        var s = document.createElement("script");
                        s.type = "text/javascript";
                        document.body.appendChild(s);
                        s.onload = function () {
                            event.source.postMessage(JSON.stringify({ task: 'html2canvasAdded'}), event.origin);
                        };
                        s.src = UST.settings.serverPath + '/lib/html2canvas/html2canvas.js';
                    } else {
                        // The script is already added
                        event.source.postMessage(JSON.stringify({ task: 'html2canvasAdded'}), event.origin);
                    }
                    break;

                // Return a screenshot of the site
                case 'screenshot':
                    // Scroll to top before trying to add the screenshot
                    jQuery(document).scrollTop(0);
                    jQuery(document).scrollLeft(0);

                    html2canvas(document.body, {
                        logging: false,
                        useCORS: false,
                        proxy: UST.settings.serverPath + '/lib/html2canvas/proxy.php'
                    }).then(function(canvas) {
                        var img = new Image();
                        img.onload = function() {
                            img.onload = null;
                            event.source.postMessage(JSON.stringify({ task: 'screenshot', img: img.src }), event.origin);
                        };
                        img.onerror = function() {
                            img.onerror = null;
                            window.console.log("Not loaded image from canvas.toDataURL");

                        };
                        img.src = canvas.toDataURL("image/png");
                    });
                    break;
            }
        }
    };

    // Send scroll event back to panel
    //iframe is scrolled
    jQuery(document).scroll(function () {
        var t = jQuery(this).scrollTop();
        var l = jQuery(this).scrollLeft();
        if (lastEvent !== null) {
            lastEvent.source.postMessage(JSON.stringify({ task: 'SCROLL', top: t, left: l }), lastEvent.origin);
        } else {
            console.log("Scroll event happened before parent call to iframe");
        }
    });

    // Triger click
    var iframeRealClick = function () {
        if (elementUnder !== null) {
            if (elementUnder.nodeName === 'SELECT') {
                jQuery(elementUnder).get(0).setAttribute('size', elementUnder.options.length);
            } else {
                var link = jQuery(elementUnder).closest('a').eq(0);
                if (link !== undefined) {
                    link = link.attr('href');
                    if (link !== undefined && (link.indexOf('//') !== -1 || link.indexOf('www.') !== -1) 
                        && link.indexOf(window.location.host) === -1)
                        link = 'external';
                }

                // Check for page leave
                if(link !== 'external') {
                    // Don't trigger click if element has class UST_noClick
                    if(!jQuery(elementUnder).closest('.UST_noClick').length) {
                        fireEvent(elementUnder, 'click');
                    } else {
                        UST.DEBUG && console.log("Didn't trigger the click. Had class UST_noClick");
                    }
                } else {
                    // User clicked on an external link, don't trigger the click
                    UST.DEBUG && console.log("Didn't trigger click on external link");
                }
            }
        }

        if (lastElement !== null && lastElement.nodeName === 'SELECT')
            jQuery(lastElement).get(0).setAttribute('size', 1);
        lastElement = elementUnder;
    };

    // Trigger hover event
    var lastHover = null;
    var lastParents = null;
    var iframeHover = function () {
        if (lastHover !== elementUnder) {
            var parents = jQuery(elementUnder).parents().addBack();

            if (lastParents !== null) {
                lastParents.removeClass("hover");
                lastParents.trigger("mouseout");
            }

            parents.addClass("hover");
            parents.trigger("mouseover");

            lastParents = parents;
        } else {
            return 1;
        }

        // No element is currently hovered
        lastHover = elementUnder;
        return 0;
    };

    // Function used to trigger click event
    var fireEvent = function (element, event) {
        // If the element already has a .click() method use that
        // HTMLElements usually have `click`
        if(event === 'click' && typeof element.click === 'function') {
            return element.click();
        }

        var evt;
        if (document.createEvent) {
            // Dispatch for firefox + others
            evt = document.createEvent("HTMLEvents");
            evt.initEvent(event, true, true); // event type,bubbling,cancelable
            return !element.dispatchEvent(evt);
        } else {
            // Dispatch for IE
            evt = document.createEventObject();
            return element.fireEvent('on' + event, evt);
        }
    };

    window.addEventListener('message', receiver, false);

})();
