/* Load and save settings from tracker.js file */
(function() {
    'use strict';

    var UST = window.UST ? window.UST : window.UST = {};

    /* global DEBUG */

    // Save settings, over-write tracker.js file
    UST.saveSettings = function() {
        DEBUG && console.log("Saving settings...");

        jQuery.ajax({
            type: "POST",
            url: 'helpers/saveSettings.php',
            data: {
                delay: jQuery('#delayRange').val(),
                static: jQuery('#staticWebsite').is(':checked'),
                recordClick: jQuery('#recordClicks').is(':checked'),
                recordMove: jQuery('#recordMove').is(':checked'),
                recordKey: jQuery('#recordKey').is(':checked'),
                maxMove: jQuery('#maxMoves').val(),
                serverPath: jQuery('#serverPath').val(),
                ignoreGET: jQuery('#ignoreGET').val(),
                ignoreIPs: jQuery('#ignoreIPs').val(),
                percentangeRecorded: jQuery('#percentangeRecorded').val()
            },
            success: function (err) {
                if(!err) alertify.alert("Settings successfully saved!");
                else alertify.alert(err);
            },
            error: function (data) {
                alertify.alert("Could not save settings!" + data.responseText);
            }
        });
    };

    // Get settings, read from tracker.js file
    UST.loadSettings = function() {
        // Don't cache the response
        return jQuery.getJSON('helpers/loadSettings.php', function (data) {
            window.settings = data;

            // Patch the settings object with the client-stored settings
            window.settings.censorIP = localStorage.censorIP !== 'false';
        }).fail(function (data) {
            if (data.responseText.indexOf('login') !== -1)
                window.location = 'login.php';
            else
                alertify.alert("Could not load settings from file." + data.responseText);
        });
    };

    if(!window.DIRECT_PLAYBACK) UST.loadSettings();
})();
