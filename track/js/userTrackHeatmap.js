/***
Heatmap drawings & interactions
***/

var userTrackHeatmap = (function () {

    var heatmap = 0, minimap;
    var emptySet = {
        max: 0,
        data: []
    };

    // Display the heatmap using heatmap.js
    function drawHeatmap() {
        DEBUG && console.log('createHeatmap');

        jQuery('#loading').stop(1, 0).fadeIn(200).text("Loading data...");

        userTrackAjax.loadHeatmapData();
        jQuery('#heatmapWrap').stop(1, 0).animate({ opacity: 1 }, 100);
        jQuery('#heatmapIframe').stop(1, 0).animate({ opacity: 1 }, 1000);
    }

    function setHeatmapData(data) {
        DEBUG && console.log('setHeatmapData called');

        // Display loading screen
        jQuery('#loading').text("Drawing canvas...");

        minimap = jQuery('#minimap'),

        // Remove old heatmap
        cleanHeatmap();

        // Create new heatmap
        heatmap = h337.create({
            container: document.getElementById("heatmap"),
            radius: options.radius
        });

        //If data received is null
        if (data === undefined || data.length === 0) {
            jQuery('#loading').text("No data stored in database...");
            return;
        }

        if (options.what == 'scrollmap') {
            drawScrollMap(data);
            return;
        }

        //Static option
        if (settings.static == "true" || settings.static === true) {
            oIframe.contentWindow.postMessage(JSON.stringify({ task: 'STATIC' }), '*');
        } else {
            firstStaticX = 0;
        }

        // Wait for above postMessage to complete
        setTimeout(function () {
            if(DEBUG) var starTime = new Date();

            // Pre-process data
            var processedData = [], obj;
            for(var i = 0; i < data.length; ++i) {
                obj = JSON.parse(data[i]);

                for(el in obj) {
                    obj[el].x = parseInt(obj[el].x) + parseInt(firstStaticX);
                    obj[el].y = parseInt(obj[el].y);
                    obj[el].value = parseInt(obj[el].count);
                    delete obj[el].count;

                    // Skip broken points
                    if (isNaN(obj[el].x) || isNaN(obj[el].y) || obj[el].y < 0) {
                        DEBUG && console.log('Invalid point:', obj[el]);
                        continue;
                    }

                    processedData.push(obj[el]);    
                }               
            }

            // Sort points by Y ascending, X asc as second criteria
            processedData.sort(function (a, b) { 
                if(a.y > b.y)
                    return 1;
                if(a.y == b.y) {
                    if(a.x > b.x)
                        return 1;
                    return 0;
                }
                // a.y < b.y
                return -1;
            });

            // Eliminate duplicate points
            data = [processedData[0]];
            var N = 0;
            var maxValue = data[0].value;
            for(i = 1; i < processedData.length; ++i) {
                // Merge data for same point
                if(data[N].y == processedData[i].y &&
                   data[N].x == processedData[i].x) {
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
            jQuery('#loading').stop(1, 0).fadeOut(200);

            DEBUG && console.log('Total points: ', data.length);
            DEBUG && console.log('Time spent drawing: ', new Date() - starTime, 'ms');
            
        }, 100);

    }

    function cleanHeatmap() {
        // Remove all other heatmap canvases
        jQuery('.heatmap-canvas').remove();

        if(!minimap) minimap = jQuery('#minimap');
        minimap.css('display', 'none');
        heatmap = 0;
    }

    function generateMinimap() {
        // !@TODO: iframe should be scrolled to top because html2canvas
        //  wants it for full-page screenshot
        // Make sure minimap height matches the resolution
        minimap.height(jQuery('#heatmapWrap').height() - 3);

        var oldCanvas = jQuery(".heatmap-canvas").get(0);

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
    function scrollMinimap(scrollTop, scrollLeft) {
        var cursor = jQuery('#minimapCursor'),
            heatmapCanvas = jQuery(".heatmap-canvas"),
            minimapCanvas = jQuery('#minimapCanvas');

        var ratio = minimap.width() / heatmapCanvas.width();
        
        // Update cursor size if necessary
        cursor.width(minimap.width() - 2);
        cursor.height(jQuery('#heatmapWrap').height() * ratio);
        scrollTop *= ratio;

        actualScroll = scrollTop;

        // If cursor over-scrolls minimap move canvas instead
        var miniH = minimap.height();
        miniH -= 3/10 * miniH;

        if(scrollTop + cursor.height() > miniH) {
            scrollTop = miniH - cursor.height();
        }

        if(actualScroll != scrollTop) {
            minimapCanvas.css('marginTop', - (actualScroll - scrollTop));
        } else {
            minimapCanvas.css('marginTop', 0);
        }

        cursor.css('top', scrollTop);
    }


    // Return public aliases
    return {
        clean: cleanHeatmap,
        setData: setHeatmapData,
        generateMinimap: generateMinimap,
        scrollMinimap: scrollMinimap,
        draw: drawHeatmap,
    };

}());
