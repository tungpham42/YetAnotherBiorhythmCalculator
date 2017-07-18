/***
Scrollmap drawing & interactions
***/

//Draw scrollmap based on user movements
function drawScrollMap(data) {

    var ctx = document.querySelector('canvas');
    ctx = ctx.getContext('2d');
    ctx.fillStyle = "rgba(100, 200, 200, 1)";
    ctx.fillRect(0, 0, 2000, 5000);

    var dataCount = 0;
    var yPos = new Array();

    yPos.push(0);

    for (i in data) {

        data[i] = JSON.parse(data[i]);

        for (move in data[i])
            yPos.push(parseInt(data[i][move].y));
    }

    // Sort ascending
    yPos.sort(function (a, b) { return a - b });

    // Remove duplicates
    yPos = yPos.filter(function (elem, pos) {
        return yPos.indexOf(elem) == pos
    });

    // Color difference between each segment
    var step = 200 / yPos.length;

    // Curent color
    var green = 0;

    for (i in yPos) {

        ctx.fillStyle = "rgb(" + (250 - Math.floor(green)) + ", 70, 100)";
        ctx.fillRect(0, yPos[i - 1 < 0 ? 0 : i - 1], 2000, yPos[i] + 100);

        green += step;
    }

    jQuery('.heatmap-canvas').css('opacity', 0.5);
    userTrackHeatmap.generateMinimap();
    //Remove loading screen
    jQuery('#loading').stop(1, 0).fadeOut(200);
}
