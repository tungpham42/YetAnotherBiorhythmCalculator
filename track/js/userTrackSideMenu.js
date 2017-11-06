/* global options, UST */

/**
 * Handles all user input on the "Heatmap settings" menu.
 */
(function($) {
    var $heatmapSizeInput = $('#heatmap-size-input');
    var $heatmapSizeValue = $('#heatmap-size-value');

    var $heatmapScaleInput = $('#heatmap-scale-input');
    var $heatmapScaleValue = $('#heatmap-scale-value');

    // Load previous settings from localStorage
    $heatmapSizeInput.val(options.radius);
    $heatmapSizeValue.text(options.radius + 'px');

    $.uniform.update();

    // Heatmap scale slider
    $heatmapScaleInput.on('input change mousemove', function () {
        var scale = $(this).val();
        $heatmapScaleValue.text(scale + '%');
        UST.Heatmap.setScale(scale / 100);
    });

    // Heatmap point size slider
    $heatmapSizeInput.on('input change mousemove', function () {
        $heatmapSizeValue.text($(this).val() + 'px');
    });

    $heatmapSizeInput.on('input', function () {
        // Update the radius value and save it to the local machine
        localStorage.radius = options.radius = $(this).val();
        UST.Heatmap.redraw();
    });
})(jQuery);
