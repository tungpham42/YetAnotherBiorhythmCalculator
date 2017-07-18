/* Load and save settings from tracker.js file */

// Object to store selected filter values
var options = {};
options.radius = 30;
options.url = '';
options.resolution = '-1';
options.what = localStorage.what !== undefined ? localStorage.what : 'movements';
options.domain = '';

// Save settings, over-write tracker.js file
function saveSettings() {
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
            percentangeRecorded: jQuery('#percentangeRecorded').val(),
        },
        success: function () {
            alert("Settings successfully saved!");
        },
        error: function (data) {
            alert("Could not save settings!" + data.responseText);
        }
    });

}

// Get settings, read from tracker.js file
function loadSettings() {
    jQuery.getJSON('helpers/loadSettings.php', function (data) {
        settings = data;
    }).fail(function (data) {
        if (data.responseText.indexOf('login') != -1)
            window.location = 'login.php';
        else
            alert("Could not load settings from file." + data.responseText);
    });

}