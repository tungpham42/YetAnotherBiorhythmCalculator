/* global DEBUG, settings */
(function ($) {
    'use strict';

    var UST = window.UST ? window.UST : window.UST = {};

    DEBUG && console.log('userTrackRendering loaded.');

    // Auxilliary arrays
    var browserCodes = ['chrome', 'opera', 'msie', 'firefox', 'mozilla', 'safari', 'mobile'];
    var browserNames = ['Google Chrome', 'Opera', 'Internet Explorer', 'Mozilla Firefox', 'Mozilla Firefox', 'Safari', 'Mobile Device'];
    var browserImages = ['chrome.png', 'opera.png', 'ie.png', 'firefox.png', 'firefox.png', 'safari.png', 'mobile.png'];

    function getFormattedDate(date) {
        // Add month name and day
        var formattedDate = date.getDate() + ' ' + date.toLocaleString("en", { month: "short" });
        // Add hour and minutes (locale string without seconds)
        formattedDate += ' <b>' + date.toLocaleTimeString().replace(/:\d+ /, ' ') + '</b>';
        return formattedDate;
    }

    const $shareButton = $('<img class="shareRecordButton" src="images/icons/share.png" title="Direct playback share link."/>');
    const $defaultFlag = $('<img width="16px" height="11px" src="images/flags/xx.png"/>');

    function getUsersListAsTrs(data) {

        var $wrap = $('<div>');

        // No clients given
        if (data === null || data.clients.length === 0) return null;

        var totalUserCount = data.count;
        data = data.clients;

        var now = new Date();
        var localTimezoneOffset = now.getTimezoneOffset();
        let timezoneOffset = -localTimezoneOffset - localStorage.serverUtcOffset;

        data.forEach((client, clientIndex) => {
            let $tr = jQuery('<tr>');
            $tr.attr('data-id', client.clientid);

            // If ID is null means there are no recordings, set correct number to 0
            if (client.recordid == null) client.nr = 0;
            if (client.referrer == null) client.referrer = '';

            // Make sure value is number
            client.nr = +client.nr;

            // Remove year and seconds from date
            let localDate = new Date(client.date);
            localDate.setMinutes(localDate.getMinutes() + timezoneOffset);
            client.date = getFormattedDate(localDate);

            var referrerMaxLength = 15;

            const columns = ['date', 'ip', 'resolution', 'browser', 'referrer', 'pageHistory', 'tags'];
            columns.forEach((dataType) => {
                var td = jQuery('<td>');
                td.addClass(dataType);
                $tr.append(td);

                switch (dataType) {
                    case 'resolution':
                        // Use .text instead of .html to prevent XSS
                        td.text(client[dataType]);
                        break;
                    case 'date':
                        // Wrap hour in bold tags
                        td.html(client.date);

                        // Add full date as title in case user wants to know seconds/year
                        td.attr('title', localDate.toLocaleString());
                        break;

                    // Made referrer shorter and make it a link
                    case 'referrer':
                        var referrerURL = client.referrer;
                        if (!referrerURL) return;
                        // Quick XSS fixx
                        referrerURL = referrerURL.replace('javascript:', '');
                        referrerURL = referrerURL.replace('<', '&lt;');

                        // eslint-disable-next-line
                        var niceURL = client.referrer.match(/^(?:https?:\/\/)?(?:[^@\/\n]+@)?(?:www\.)?([^:\/\n]+)/im)[1];
                        if (!niceURL) return;

                        if (niceURL.length > referrerMaxLength) {
                            niceURL = niceURL.substring(0, referrerMaxLength - 3) + '...';
                        }

                        var $a = jQuery('<a target="_blank" href="' + referrerURL + '" class=""></span>');
                        $a.attr('title', "Open in new tab: <b>" + referrerURL + "</b>");
                        $a.text(niceURL);
                        td.html($a);
                        break;

                    // Wrap tags in spans
                    case 'tags':
                        td.html('<span class="plus addTag" data-id ="' + client.clientid + '">+</span>');
                        td.attr('title', '<b>Click</b> to add this tag filter. <br/> <b>Right click</b> a tag to remove it');
                        var tags = client[dataType];
                        for (var idx in tags) {
                            var span = jQuery('<span class="tag"></span>');
                            span.text(tags[idx]);
                            td.append(span);
                        }
                        break;

                    // Add placeholder for flag
                    case 'ip':

                        var newIP = client.ip;

                        if (settings.censorIP === true) {
                            newIP = '*' + client.ip.slice(-7);
                        }

                        // Check first if flag image is cached
                        if (localStorage['c' + client.ip] !== undefined) {
                            var countryCode = localStorage['c' + client.ip];
                            var titleInfo = countryCode;

                            // If we have countryCode|titleInfo 
                            if (countryCode.length > 2) {
                                var parts = countryCode.split('|');
                                countryCode = parts[0];
                                titleInfo = parts[1];
                            }
                            td.html('<img width="16px" height="11px" src="images/flags/' + countryCode + '.png" title="' + titleInfo + '"/> ' + newIP);
                        }
                        else {
                            // By default show the N/A flag
                            td.append($defaultFlag.clone(), newIP);

                            // Timeout delay so that the flag api doesn't break
                            (function (ip, td, newIP) {
                                setTimeout(function () { UST.API.addCountryFlag(ip, td, newIP); }, 300 * clientIndex);
                            })(client.ip, td, newIP);
                        }
                        break;

                    case 'browser':
                        // Display browser icon
                        var browserEl = td.get(0);
                        var br = client.browser;
                        var browserVersion = br.split(' ').pop();
                        for (var name in browserCodes) {
                            var code = browserCodes[name];
                            if (br.indexOf(code) !== -1) {
                                // Don't show the 'mobile' text next to the mobile icon
                                if (code === 'mobile') browserVersion = '';

                                var d = '<img src="images/icons/' + browserImages[name] + '" title="' + browserNames[name] + ' ' + browserVersion + '"/>';
                                d += '<span>' + browserVersion + '</span>';

                                browserEl.innerHTML = d;
                                break;
                            }
                        }

                        break;
                    // Show number of records for client
                    case 'pageHistory':

                        td.empty();
                        td.append(client.nr + ' page' + (client.nr !== 1 ? 's' : ''));

                        // Compute the time spent in hours, minutes, seconds
                        var timeSpentString = '';
                        var ts = client.timeSpent;
                        if (ts > 3600) {
                            var hours = ts / 3600 | 0;
                            timeSpentString += hours + 'h ';
                            ts -= hours * 3600;
                        }

                        if (ts > 60) {
                            var mins = ts / 60 | 0;
                            timeSpentString += mins + 'm ';
                            ts -= mins * 60;
                        }

                        timeSpentString += ts + 's';

                        td.append(' in ' + timeSpentString + ':');

                        var pageList = client[dataType].split(' ');

                        for (var index in pageList) {

                            var pageName = pageList[index];

                            // Remove page extension
                            // Consider that extensions are max 5 characters
                            var dotPosition = pageName.lastIndexOf('.');
                            if (dotPosition !== -1 && pageName.length - dotPosition - 1 < 5) {
                                pageName = pageName.slice(0, pageName.lastIndexOf('.'));
                            }

                            pageList[index] = '<div class="pageEntry">' + pageName + '</div>';
                            td.append(pageList[index]);
                        }

                        td.attr('width', '50%');
                        break;
                }
            });

            // Disable the button if it has 0 recordings
            var disabled = client.nr === 0 ? ' disabled title="No movements were recorded. Client may have left immediately." ' : '';

            // Get first page visited
            var firstPage = client.pageHistory;
            if (client.pageHistory.indexOf(' ') !== -1) {
                firstPage = client.pageHistory.split(' ')[0];
            }

            // Watched class
            var watchedClass = client.watched && client.watched !== '0' ? 'watched' : '';

            // Display the play button
            var recordColumn = jQuery(
                `<td class="playButton ${watchedClass}">
                            <button 
                            data-recordid="${client.recordid}" 
                            data-page="${firstPage}" 
                            data-resolution="${client.resolution}"
                            data-clientpageid="${client.clientpageid}"
                            ${disabled}>Play
                            </button>
                        </td>`);


            recordColumn.append($shareButton.clone());
            $tr.append(recordColumn);

            $wrap.append($tr);
        });

        return {
            count: totalUserCount,
            contents: $wrap.contents()
        };
    }

    UST.Rendering = {
        getUsersListAsTrs: getUsersListAsTrs
    };
})(jQuery);
