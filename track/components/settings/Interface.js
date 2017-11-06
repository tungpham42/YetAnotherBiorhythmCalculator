/* global settings */
(function() {
    'use strict';
    var UST = window.UST ? window.UST : window.UST = {};

    // Append the content to the document
    jQuery.get('components/settings/index.html').then((data) => {
        jQuery('body').append(jQuery.parseHTML(data));

        // Keep UI labels synced when settings are changed
        jQuery('#delayRange').on('change mousemove', function () {
            jQuery('#range_value').text(jQuery(this).val() + 'ms');
        });

        jQuery('#maxMoves').on('change mousemove', function () {
            jQuery('#range_value2').text(jQuery(this).val());
        });

        jQuery('#percentangeRecorded').on('change mousemove', function () {
            jQuery('#range_value3').text(jQuery(this).val() + '%');
        });

        // Update the censorIP checkbox based on the locally saved setting
        jQuery('#censorIP').prop('checked', localStorage.censorIP !== 'false');

        jQuery('#censorIP').change(function () {
            if (!jQuery(this).is(':checked'))
                localStorage.censorIP = 'false';
            else
                localStorage.censorIP = 'true';
        });

        /*** Uniformjs ***/
        jQuery("select,input").uniform();

        UST.showSettings = function () {
            jQuery('#delayRange').val(settings.delay).trigger('change');
            jQuery('#maxMoves').val(settings.maxMoves).trigger('change');
            jQuery('#staticWebsite').prop('checked', settings.static == "true");
            jQuery('#recordClicks').prop('checked', settings.recordClicks == "true");
            jQuery('#recordMove').prop('checked', settings.recordMoves == "true");
            jQuery('#recordKey').prop('checked', settings.recordKey == "true");
            jQuery('#serverPath').val(settings.serverPath).trigger('change');
            jQuery('#ignoreGET').val(eval(settings.ignoreGET)).trigger('change');
            jQuery('#ignoreIPs').val(eval(settings.ignoreIPs)).trigger('change');
            jQuery('#percentangeRecorded').val(settings.percentangeRecorded).trigger('change');
            
            jQuery('#settings').center();
            jQuery('#settings').centerv();
            jQuery('#settings').fadeIn(300);
            jQuery.uniform.update();
        };
    });
})();
