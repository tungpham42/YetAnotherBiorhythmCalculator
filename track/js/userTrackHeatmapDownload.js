'use strict';

var userTrackDownload = {};

(function($) {

    // Document ready
    $(function(){
        $('#downloadHeatmap').click(function() {
            jQuery('#loading').show().text("Adding the html2canvas library...");
            oIframe.contentWindow.postMessage(JSON.stringify({ task: 'addHtml2canvas' }), "*");
        });
    });

}(jQuery));


/**
 * This starts when the screenshot is received from the iframe.
 * The order of actions is: click button->add html2canvas
 *
 * We need the base64 instead of the image object so we can send it as JSON.
 * @param  {Base64 String} base64Screenshot The screenshot of the iframe.
 */
userTrackDownload.start = function(base64Screenshot) {

    // Draw heatmap over the screenshot
    jQuery('#loading').show().text("Downloading the heatmap.");
    var heatmapCanvas = jQuery('.heatmap-canvas').get(0);
    
    var auxCanvas = sameSizeCanvas(heatmapCanvas);
    var context = auxCanvas.getContext('2d');

    // Draw the screenshot on the new canvas
    var screenshot = new Image();
    screenshot.onload = function() {
        context.drawImage(screenshot, 0, 0, auxCanvas.width, auxCanvas.height);

        // Draw the heatmap on top
        context.save();
        // Change opacity if it is scrollmap
        if(options.what === "scrollmap") {
            context.globalAlpha = parseFloat($(heatmapCanvas).css('opacity'));
        }
        context.drawImage(heatmapCanvas, 0, 0);
        context.restore();

        // Trigger the download of the image
        var a = document.createElement('a');
        a.href = auxCanvas.toDataURL("image/png");
        a.download = "userTrack_heatmap_" + options.domain + ".png";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        jQuery('#loading').hide(100);

    }
    screenshot.src = base64Screenshot;
}

function sameSizeCanvas(oldCanvas) {
    //create a new canvas
    var newCanvas = document.createElement('canvas');

    //set dimensions
    newCanvas.width = oldCanvas.width;
    newCanvas.height = oldCanvas.height;
    //return the new canvas
    return newCanvas;
}
