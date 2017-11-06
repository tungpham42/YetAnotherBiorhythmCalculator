/* global DEBUG, h337, options, settings, drawScrollMap */

/**
 * Heatmap drawings & interactions
 **/ 
(function ($) { 
    'use strict';
    var UST = window.UST ? window.UST : window.UST = {};

    UST.Heatmap = (function () {
        var heatmap = 0;
        var minimap;
        var heatmapDOMelement = document.getElementById("heatmap");
        var $heatmapWrap = $('#heatmapWrap');
        var $heatmapIframe = $('#heatmapIframe');
        var $heatmapScaleInput = $('#heatmap-scale-input');


        // Display the heatmap using heatmap.js
        function drawHeatmap () {
            DEBUG && console.log('createHeatmap');

            $('#loading').stop(1, 0).fadeIn(200).text("Loading data...");

            UST.API.loadHeatmapData().then(setHeatmapData);
            $heatmapWrap.stop(1, 0).animate({ opacity: 1 }, 100);
            $heatmapIframe.stop(1, 0).animate({ opacity: 1 }, 1000);
        }

        /**
         * Calls the h337.configure method on the heatmap in order to redraw it.
         * Used when options.radius is changed.
         */
        function redraw () {
            DEBUG && console.log('heatmap redraw called');

            if(!heatmap) return console.error('Heatmap was never created.');

            // Configure triggers repaint
            // Normally the radius isn't updated, the heatmap.js file was monkey-patched
            heatmap.configure({
                radius: options.radius
            });
        }

        var VAL_SEPARATOR = UST.DATA_VAL_SEPARATOR = '_';
        var EVT_SEPARATOR = UST.DATA_EVT_SEPARATOR = '~';
        UST.DATA_KEY_SEPARATOR = '`';

        /**
         * Converts the given data from 
         * 
         * from [x_y_count]
         * to [JSON]:
         * {x:, y:, count}
         * 
         * @param  {Array[String]} data Array of compressed events
         * @return {Array[Object]}      Aray of JS Objects
         */
        function uncompress(data) {
            // First join the mulitple rows
            data = data.join(EVT_SEPARATOR);
            // Then split each event into it's own element
            data = data.split(EVT_SEPARATOR);

            return data.map(el => {
                var event = el.split(VAL_SEPARATOR);
                return {
                    x: +event[0],
                    y: +event[1],
                    count: +event[2]
                };
            });
        }

        function setHeatmapData (data) {
            // If data received is null
            if (!data) {
                $('#loading').text("No data stored in database...");
                return;
            }

            // Convert data to Object from the compressed format
            data = uncompress(data);

            DEBUG && console.log('setHeatmapData called');

            // Display loading screen
            $('#loading').text("Drawing canvas...");

            minimap = $('#minimap'),

            // Remove old heatmap
            cleanHeatmap();

            // Create new heatmap
            heatmap = h337.create({
                container: heatmapDOMelement,
                radius: options.radius
            });

            if (options.what === 'scrollmap') {
                drawScrollMap(data);
                return;
            }

            // Static option
            if (settings.static === "true" || settings.static === true) {
                UST.iframe.contentWindow.postMessage(JSON.stringify({ task: 'STATIC' }), '*');
            } else {
                UST.firstStaticX = 0;
            }

            // Wait for above postMessage to complete
            setTimeout(function () {
                if(DEBUG) var starTime = new Date();

                // Pre-process data
                var processedData = [];
                for(var i = 0; i < data.length; ++i) {
                    var obj = Object.assign({}, data[i]);

                    obj.x += +UST.firstStaticX;
                    obj.y = obj.y;
                    obj.value = obj.count;
                    delete obj.count;

                    // Skip broken points
                    if (isNaN(obj.x) || isNaN(obj.y) || obj.y < 0) {
                        DEBUG && console.log('Invalid point:', obj);
                        continue;
                    }

                    processedData.push(obj);    
                }

                // Sort points by Y ascending, X asc as second criteria
                processedData.sort(function (a, b) { 
                    if(a.y > b.y)
                        return 1;

                    if(a.y === b.y) {
                        if(a.x > b.x)
                            return 1;
                        if(a.x < b.x)
                            return -1;
                        return 0;
                    }

                    return -1;
                });

                // Eliminate duplicate points
                data = [processedData[0]];
                var N = 0;
                var maxValue = data[0].value;
                for(i = 1; i < processedData.length; ++i) {
                    // Merge data for same point
                    if(data[N].y === processedData[i].y &&
                    data[N].x === processedData[i].x) {
                        data[N].value += processedData[i].value;
                    } else {
                        // Otherwise create new point
                        data.push(processedData[i]);
                        ++N;
                    }

                    if(data[N].value > maxValue)
                        maxValue = data[N].value;
                }

                heatmap.setData({max: maxValue, data: data});
                generateMinimap();
                $('#loading').stop(1, 0).fadeOut(200);

                DEBUG && console.log('Total points: ', data.length);
                DEBUG && console.log('Time spent drawing: ', new Date() - starTime, 'ms');
                
            }, 100);

        }

        function cleanHeatmap () {
            // Remove all other heatmap canvases
            $('.heatmap-canvas').remove();

            if(!minimap) minimap = $('#minimap');
            minimap.css('display', 'none');
            heatmap = 0;
        }

        function generateMinimap () {
            // !@TODO: iframe should be scrolled to top because html2canvas
            //  wants it for full-page screenshot
            // Make sure minimap height matches the resolution
            minimap.height($('#heatmapWrap').height() - 3);

            var oldCanvas = $(".heatmap-canvas").get(0);

            scrollMinimap(0, 0);

            // Create a new canvas
            var newCanvas = document.getElementById("minimapCanvas");
            var context = newCanvas.getContext('2d');

            // Set dimensions, scaling the hegiht accordingly
            newCanvas.width = minimap.width();
            var ratio = newCanvas.width / oldCanvas.width;
            newCanvas.height = oldCanvas.height * ratio;

            // Apply the old canvas to the new one
            context.drawImage(oldCanvas, 0, 0, oldCanvas.width, oldCanvas.height,
                                         0, 0, newCanvas.width, newCanvas.height); 

            minimap.show(200);
        }

        /**
         * Moves the minimap cursor when the iframe is scrolled
         * @param  {Number} scrollTop  
         * @param  {Number} scrollLeft
         */
        function scrollMinimap (scrollTop, scrollLeft) {
            var cursor = $('#minimapCursor'),
                heatmapCanvas = $(".heatmap-canvas"),
                minimapCanvas = $('#minimapCanvas');

            var ratio = minimap.width() / heatmapCanvas.width();
            
            // Update cursor size if necessary
            cursor.width(minimap.width() - 2);
            cursor.height($('#heatmapWrap').height() * ratio);
            scrollTop *= ratio;

            var actualScroll = scrollTop;

            // If cursor over-scrolls minimap move canvas instead
            var miniH = minimap.height();
            miniH -= 3 / 10 * miniH;

            if(scrollTop + cursor.height() > miniH) {
                scrollTop = miniH - cursor.height();
            }

            if(actualScroll !== scrollTop) {
                minimapCanvas.css('marginTop', - (actualScroll - scrollTop));
            } else {
                minimapCanvas.css('marginTop', 0);
            }

            cursor.css('transform', 'translate3d(0, ' + scrollTop + 'px, 0)');
        }

        function setHeatmapScale(scale) {
            $heatmapWrap.css('transform', 'scale(' + scale + ')');
        }

        function setIframeScale(scale) {
            $heatmapIframe.css('transform', 'scale(' + scale + ')');
        }

        /**
         * Sets the scale transform property to both the heatmap layer
         * and the underlying iframe page.
         * 
         * @param {Number} scale The scale to set, in percentages [1-100]
         */
        function setIframeAndHeatmapScale (scale) {
            setIframeScale(scale);
            setHeatmapScale(scale);
        }

        /**
         * Calls setIframeAndHeatmapScale to fit the width of the
         * iframe into screen;
         */
        function fitIframeAndHeatmapScale () {
            var availableWidth = $(window).width();
            var fittedScale = availableWidth / ($heatmapIframe.width() + 5);

            // Also update the UI
            $heatmapScaleInput[0].value = fittedScale * 100 | 0;
            $heatmapScaleInput.trigger('change');
        }

        // Return public aliases
        return {
            clean: cleanHeatmap,
            setData: setHeatmapData,
            generateMinimap: generateMinimap,
            scrollMinimap: scrollMinimap,
            redraw: redraw,
            draw: drawHeatmap,
            setScale: setIframeAndHeatmapScale,
            fitScale: fitIframeAndHeatmapScale,
            setHeatmapScale: setHeatmapScale
        };
    })();
}(jQuery));
