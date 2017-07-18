define(
[
    './EventBus',
    'underscore'
],

function(eventBus){
    "use strict";

    var undefined,
        isTouchDevice = ('ontouchstart' in window) || (window.DocumentTouch && document instanceof DocumentTouch),
        eventsMap = {
            click:     "touchstart",
            mousedown: "touchstart",
            mousemove: "touchmove",
            mouseup:   "touchend"
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
            return function F(elem, type, callback) {
                if (!elem) return;
                if (eventsMap[type])
                    elem.addEventListener(eventsMap[type], callback, false);
                elem.addEventListener(type, callback, false);
            }
        } else {
            return function F(elem, type, callback) {
                if (!elem) return;
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
        elem.className = trim((" "+elem.className+" ").replace(" "+klass, ""));
    }

    var anchor = document.createElement("a");

    function getData(url, channel) {
        require(['text!' + getAbsoluteUrl(url) + "?a=" + (new Date).getTime()], function(data){
            eventBus.emit('data.arrived', data, channel);
        });
    }

    // in local we can't load txt files
    // so load the js version and convert it to know format
    function getLocalData(url, channel) {
        url = getAbsoluteUrl(url).replace(/\.txt$/, ".js");

        require([url], function(data){
            // convert object to know format (.txt)
            var text = [];
            _.each(data.options, function(value, key){
                text.push("# "  + key + ": " + value);
            });

            _.each(data.data, function(value, key){
                text.push(key + " | " + value);
            });

            eventBus.emit('data.arrived', text.join("\n"), channel);
        });
    }

    function getAbsoluteUrl(url) {
        return location.href.split("/").slice(0, -1).join("/") + "/" + url;
    }
    
    // random integer between min-max
    function randint(min, max) {
        return parseInt(Math.random()*(max-min+1)) + min;
    }

    // return random lowercase character
    // we can't use choice because IE doesn't allow string access by index
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

    /* find element position in the page */
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
    
    // unicode combining marks
    var rMarks = /[\u0300-\u036F\u1DC0-\u1DFF\u20D0-\u20FF\uFE20-\uFE2F\u0483-\u0489\u0591-\u05BD]/;
    
    function stringLength(string) {
        var length = string.length,
            count  = length,
            i;
        
        for (i = 0; i < length; i++) {
            if (rMarks.test(string.charAt(i))) {
                count--;
            }
        }
            
        return count;
    }
    
    function splitString(string) {
        var length = string.length,
            chars  = [],
            i;
        
        for (i = 0; i < length; i++) {
            if (rMarks.test(string.charAt(i))) {
                chars[chars.length-1] += string.charAt(i);      
            } else {
                chars.push(string.charAt(i));
            }
        }
        
        return chars;
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
    stringLength: stringLength,
    splitString: splitString,
    addClass: addClass,
    removeClass: removeClass,
    parseQueryString: parseQueryString,
    getData: location.protocol.match(/https?/) ? getData : getLocalData,
    on: addEvent,
    randint: randint,
    randchar: randchar,
    choice: choice,
    trim: trim,
    position: findPosition,
    now: function() { return (new Date).getTime(); }
};

})
