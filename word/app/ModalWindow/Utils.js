define(
[
    'underscore'
],

function(){
    "use strict";

    var undefined;
    var isMobile  = navigator.userAgent.match(/mobile/i),
        isTouchDevice = isMobile && (('ontouchstart' in window) ||
                                     window.DocumentTouch && 
                                     document instanceof DocumentTouch),
        eventsMap = {
            click: "touchstart",
            mousedown: "touchstart",
            mousemove: "touchmove",
            mouseup: "touchend"
        };

    /**********************************
     * cross browser addEventListener *
     **********************************/
    function normalizeEvent(e) {
        e = e || event;
        if (!e.preventDefault)
            e.preventDefault = function() { this.returnValue = false; };

        if (!e.stopPropagation)
            e.stopPropagation = function() { this.cancelBubble = true; };

        return e;
    }

    var addEvent = (function() {
        if (document.addEventListener) {
            return function(elem, type, callback) {
                if (!elem) return;
                type = isTouchDevice ? eventsMap[type] : type;
                elem.addEventListener(type, callback, false);
            }
        } else {
            return function(elem, type, callback) {
                if (!elem) return;
                type = isTouchDevice ? eventsMap[type] : type;
                elem.attachEvent("on"+type, function(e) {
                    e = normalizeEvent(e);
                    return callback.call(e.target || e.srcElement, e);
                });
            }
        }
    }());

    function addClass(elem, klass) {
        elem.className += " " + klass;
    }

    function removeClass(elem, klass) {
        elem.className = elem.className.replace(" "+klass, "");
    }
    
    // random integer between min-max
    function randint(min, max) {
        return parseInt(Math.random()*(max-min+1)) + min;
    }

    // return random lowercase character
    function randchar(chars) {
        return chars.charAt(randint(0, chars.length-1));
    }
    
    // select random element from a list
    function choice(a) {
        return a[randint(0, a.length-1)] || a[0];
    }
    
    // remove white space from begin and end of a string
    function trim(s) {
        return s.replace(/^\s+|\s+$/g, "");
    }

    // getElementByid wrapper
    function $(id) {
        return document.getElementById(id);
    }

    /* find position in the page */
    function findPosition(element) {
        var curleft = 0, curtop = 0;

        if (element.offsetParent) {
            do {
                curleft += element.offsetLeft;
                curtop  += element.offsetTop;
            } while (element = element.offsetParent);
        }

        return { x: curleft, y: curtop };
    }

    function parseQueryString() {
        if (location.query) { return; }
        
        var parts = location.search.replace(/^[?]/, "").split("&"),
            i     = 0,
            l     = parts.length,
            part,
            GET   = {};

        for (; i < l; i++) {
            if (!parts[i]) { continue; }
            part = parts[i].split("=");
            GET[unescape(part[0])] = decodeURI(part[1]);
        }

        return GET;
    }

    function fullScreen() {
        if (document.documentElement.scrollHeight<window.outerHeight/window.devicePixelRatio) {
            document.body.style.height = (window.outerHeight/window.devicePixelRatio)+1+"px";
            setTimeout(function(){ window.scrollTo(1, 1); }, 0);
        } else {
            window.scrollTo(1, 1);
        }
    }

return {
    fullScreen: fullScreen,
    isTouchDevice: isTouchDevice,
    $: $,
    addClass: addClass,
    removeClass: removeClass,
    parseQueryString: parseQueryString,
    on: addEvent,
    randint: randint,
    randchar: randchar,
    choice: choice,
    trim: trim,
    position: findPosition,
    now: function() { return (new Date).getTime(); }
};

})
