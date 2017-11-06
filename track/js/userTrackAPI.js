/* global DEBUG, options, updateToolTips, bindClickToList */
'use strict';

var UST = window.UST ? window.UST : window.UST = {};

/***
 Ajax requests sent to the server
***/
UST.API = (function () {

    //In case anything goes wrong
    function catchFail() {
        // Only display the message if no other message is visible
        if (jQuery('.alertify').length > 0) return;

        alertify.alert("Something went wrong on the server-side. Please try again!");
    }

    // Display list of resolutions window sizes available in db
    // Returns: Promise
    // The .fail() is treated in this function
    function getResolutions() {

        if (options.url === undefined) {
            alertify.alert('No pages saved in the database. Database may be empty.');
            return;
        }

        return jQuery.ajax({
            type: 'POST',
            dataType: "json",
            url: 'helpers/getResolutions.php',
            data: { url: options.url, domain: options.domain },
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/j-son;charset=UTF-8");
                }
            }
        }).fail((data) => {
            alertify.alert(data.responseText);
        });
    }

    // Display list of pages available in db
    function getPages() {
        return jQuery.ajax({
            type: 'POST',
            dataType: "json",
            url: 'helpers/getPages.php',
            data: { domain: options.domain },
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/j-son;charset=UTF-8");
                }
            }
        }).fail((data) => {
            if (data.responseText.indexOf('login') !== -1)
                window.location = 'login.php';
            else
                alertify.alert("Could not load pages list from db." + data.responseText);
        });
    }

    // Delete records that go over the specified limit
    function limitRecordNumber() {
        jQuery.ajax({
            type: 'POST',
            dataType: "json",
            url: 'helpers/limitRecordNumber.php',
            data: {
                domain: options.domain
            },
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/j-son;charset=UTF-8");
                }
            },
            success: function () { },
            error: function (data) {
                alertify.alert(data.responseText);
            }
        });
    }

    // Set the max number of recordings saved for this domain
    function setRecordLimit(domain, limit) {
        jQuery.ajax({
            type: 'POST',
            url: 'helpers/setRecordLimit.php',
            data: {
                limit: limit,
                domain: domain
            },
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/j-son;charset=UTF-8");
                }
            },
            success: function () { },
            error: function (data) {
                alertify.alert(data.responseText);
            }
        });
    }

    function getUtcOffset() {
        return jQuery.get('helpers/getUTCMinutesOffset.php')
            .fail(function (data) {
                alertify.alert(data.responseText);
            });
    }
    //Get the max number of recordings saved for this domain
    function getRecordLimit(domain, callback, elIndex) {
        jQuery.post("helpers/getRecordLimit.php", { domain: domain })
            .done(function (data) {
                callback(elIndex, data);
            })
            .fail(function (data) {
                alertify.alert(data.responseText);
            });
    }


    // Get available clients
    function populateClientsList(from, selfTriggered) {
        clearTimeout(UST.autoPopulateTimeout);

        // We don't need this in direct playback mode
        if (window.DIRECT_PLAYBACK) return;

        // If at least a record is selected don't refresh the list
        if (selfTriggered) {
            var selectedRecordsCount = jQuery('#recordList tr.selected').length;
            if (selectedRecordsCount > 0) {
                UST.autoPopulateTimeout = setTimeout(() => populateClientsList(from, 1), UST.autoPopulateInterval);
                return;
            }
        }

        // Filters
        var take = +jQuery('#numberFilter select').val();
        var startDate = jQuery('#rangeFilter input[name="from"]').val();
        var endDate = jQuery('#rangeFilter input[name="to"]').val();
        var tagFilters = jQuery('.tagFilterElement .value').map(function () {
            return jQuery(this).text();
        }).get();
        var liveOnly = options.liveSelected;
        jQuery.ajax({
            type: 'POST',
            dataType: "json",
            url: 'helpers/getClients.php',
            data: {
                from: from,
                take: take,
                domain: options.domain,
                startDate: startDate,
                endDate: endDate,
                tagFilters: tagFilters,
                liveOnly: liveOnly,
                order: localStorage.order
            }
        }).then((data) => {

            var $recordListTable = jQuery('#recordList table');

            // Remove all rows except th
            jQuery('tr:not(:first-child)', $recordListTable).remove();

            var usersListRows = UST.Rendering.getUsersListAsTrs(data);

            // Reset pagination
            var $pagination = jQuery('#pagination');
            $pagination.html('');

            if (usersListRows === null) {
                if (options.liveSelected) {
                    $recordListTable.append('<tr><td colspan="8"><h3>No users are currently navigating your site.</h3></td></tr>');
                } else {
                    $recordListTable.append('<tr><td colspan="8"><h3>Database is empty.</h3></td></tr>');
                }
            } else {
                $recordListTable.append(usersListRows.contents);

                var cnt = usersListRows.count;
                if (cnt) {
                    var totalPages = (cnt / take) + (cnt % take !== 0) | 0;
                    var currentPage = (from / take) + 1;

                    if (totalPages > 1) {
                        // Display each page, also select the current page
                        for (var i = 1; i <= totalPages; ++i) {
                            var selected = i === currentPage ? "selected" : "";
                            $pagination.append('<span class="' + selected + '">' + i + '</span>');
                        }
                    }
                }
            }

            updateToolTips('#recordList');
            bindClickToList();

            // Auto-update the list if we're on liveMode
            if (options.liveSelected) {
                UST.autoPopulateTimeout = setTimeout(() => populateClientsList(from, 1), UST.autoPopulateInterval);
            }
        }).fail((data) => {
            alertify.alert(data.responseText);
            console.log(data.responseText);
        });
    }

    // Get country name by ip
    function addCountryFlag(ip, td, newIP) {
        if (ip === '127.0.0.1' || ip === 'localhost') return;

        // Use local proxy for IPV6 only
        var url = ip.length > 15 ? 'helpers/getCountry.php' : ('//geoip.nekudo.com/api/' + ip);

        jQuery.ajax({
            type: 'POST',
            url: url,
            data: { ip: ip },
            success: function (data) {
                var titleInfo = '-';

                // Try converting JSON data from string if not already converted
                // eslint-disable-next-line
                try { data = JSON.parse(data); } catch (e) { }

                // We got the data from freegeoip
                if (data.country) {
                    titleInfo = data.city + ', ' + data.country.name;
                    data = data.country.code;
                    // We got the data from ipinfodb
                } else if (data.countryName) {

                    titleInfo = data.cityName + ', ' + data.countryName;
                    data = data.countryCode;
                }

                if (data === 'ZZ' || !data) data = 'xx';

                if (data.length === 2) {
                    td.html('<img width="16px" height="11px" src="images/flags/'
                        + data.toLowerCase() + '.png" title="' + titleInfo + '"/> ' + newIP);
                    if (data.toLowerCase() !== 'xx')
                        // Separate country code from titleInfo using |
                        localStorage.setItem('c' + ip, data.toLowerCase() + '|' + titleInfo);
                }
            },
            error: function () { }
        });
    }

    // Get next record after the one for the given `clientPageID`
    function getNextRecord(clientPageID) {
        return jQuery.ajax({
            type: 'POST',
            dataType: "json",
            url: 'helpers/getNextRecord.php',
            data: {
                id: clientPageID,
                domain: options.domain
            }
        }).fail(data => {
            alertify.alert(data.responseText);
        });
    }

    // Load record data for the given clientPageID
    function getRecord(clientPageID) {
        // If we are in direct playback mode only try go get full record data
        if (window.DIRECT_PLAYBACK && options.what !== 'record') return;

        options.clientPageID = clientPageID;

        return jQuery.ajax({
            type: "POST",
            dataType: "text",
            data: {
                clientPageID: clientPageID,
                page: options.url,
                resolution: options.resolution,
                what: options.what,
                directPlayback: window.DIRECT_PLAYBACK,
                clientid: window.CLIENT_ID,
                key: window.PUBLIC_KEY
            },
            url: 'helpers/recordings/getByClientPageID.php'
        }).fail(data => {
            alertify.alert("Could not load data!" + data.responseText);
        });
    }

    // Load all recorded pages based on clientID
    function getRecordList(clientID) {
        // @TODO: This should be set somewhere else.
        options.currentClientID = clientID;
        
        return jQuery.ajax({
            type: "POST",
            dataType: "json",
            data: { clientID: clientID },
            url: 'helpers/getRecordList.php'
        }).fail((data) => {
            alertify.alert("Could not load data!" + data.responseText);
        });
    }

    function loadHeatmapData() {
        DEBUG && console.log("loadHeatmapData");

        return jQuery.ajax({
            type: "POST",
            dataType: "json",
            data: {
                page: options.url, resolution: options.resolution,
                what: options.what, domain: options.domain
            },
            url: 'getData.php',
            beforeSend: function (x) {
                jQuery('#loading').text("Retrieving data from database...");
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/j-son;charset=UTF-8");
                }
            }
        }).fail(data => {
            console.log(data);
            if (data.responseText.indexOf('login') !== -1)
                window.location = 'login.php';
            else
                alertify.alert("Could not load heatmap data." + data.responseText);
        });
    }

    function addTag(clientID, tag, cb) {
        if (typeof tag === 'undefined' || tag.length === 0) {
            console.log("Tag cannot be empty!");
            return 0;
        }

        jQuery.post('addTag.php', {
            clientID: clientID,
            tagContent: tag
        }).done(function (data) {
            if (data !== '') {
                alertify.error("Tag was not added. You can not have duplicate tags for the same user.");
                console.log(data);
                return;
            }

            DEBUG && console.log('Tag ' + tag + 'added');
            if (typeof cb === 'function') cb();
        }).fail(function (data) {
            alertify.error("Something went wrong. Tag was not added.");
            console.log(data.responseText);
        });

        return 1;
    }

    function removeTag(clientID, tag, cb) {
        if (typeof tag === 'undefined' || tag.length === 0) {
            console.log("Tag cannot be empty!");
            return 0;
        }

        jQuery.post('helpers/removeTag.php', {
            clientID: clientID,
            tagContent: tag
        }).done(function (data) {
            if (data !== '') {
                alertify.error("Tag was not removed.");
                console.log(data);
                return;
            }

            DEBUG && console.log('Tag ' + tag + 'removed');
            if (typeof cb === 'function') cb();

        }).fail(function (data) {
            alertify.error("Something went wrong. Tag was not removed.");
            console.log(data.responseText);
        });

        return 1;
    }

    function getShareToken(clientID) {
        return jQuery.post('helpers/sharing/getPublicRecordingToken.php', {
            clientID: clientID
        }).fail(function (data) {
            alertify.error("Something went wrong. Tag was not removed.");
            console.log(data.responseText);
        });
    }

    function setRecordingWatched(clientID) {
        return jQuery.post('helpers/recordings/setWatched.php', {
            clientID
        }).then(data => {
            if (!data) return;
            throw new Error(data);
        }).fail(data => {
            alertify.alert("Recording could not be marked as watched.");
            console.log(data.responseText || data);
        });
    }

    // Delete a single record (page) from a client session
    function deleteRecord(id) {

        if (id > 0) {
            jQuery.post('helpers/deleteRecord.php', { recordid: id })
                .done(function () {
                    alertify.alert('Record deleted!');
                })
                .fail(function (data) {
                    console.log(data);
                    alertify.alert("Could not delete record!" + data.responseText);
                });
        }
        else {
            alertify.alert('Incorect id format.');
            return 0;
        }
    }

    // Delete client including all data related to it
    function deleteClient(clientID, el) {
        jQuery.post('helpers/deleteClient.php', { clientID: clientID })
            .done(function () {
                jQuery(el).slideUp(200, function () { jQuery(this).remove(); });
            })
            .fail(function (data) {
                console.log(data);
                alertify.alert("Could not delete client!" + data.responseText);
            });
    }

    function cleanDataForDomain(domain) {
        jQuery.post('helpers/cleanDatabase.php', { domain: domain }, function (data) {
            if (data === '') alertify.alert('All data stored in the database has been deleted.');
            else alertify.alert("Error: " + data);
            window.location.reload();
        });
    }

    function deleteZeroRecords(domain) {
        jQuery.post('helpers/deleteZeroRecords.php', { domain: domain }, function (data) {
            if (data === '')
                alertify.alert('Sessions with 0 data have been deleted.');
            else alertify.alert("Error: " + data);
            window.location.reload();
        });
    }

    //Get users list
    function getUsersList(callback) {

        jQuery.getJSON('helpers/users/getUserList.php', function (data) {
            callback(data);
        });
    }

    // Set user name or level
    function setUserData(dataType, value, id, shouldLogout) {

        //Match variable name with database field name
        if (dataType.indexOf('name') !== -1)
            dataType = 'name';

        jQuery.post('helpers/users/setUserData.php', { dataType: dataType, value: value, userId: id })
            .done(function (data) {
                if (data !== '') {
                    alertify.alert(data);
                } else if (shouldLogout) {
                    window.location = 'helpers/users/logout.php';
                }
            }).fail(catchFail);
    }

    // Add or remove domain access for user
    function changeUserAccess(type, domain, userid) {
        jQuery.post('helpers/users/changeAccess.php', { type: type, domain: domain, userid: userid })
            .done(function (data) {
                if (data !== '') alertify.alert(data); // Show error message
                else location.reload(); // Reload the page if login was ok
            })
            .fail(catchFail);
    }

    // Add new user to the db
    function addUser(name, pass) {
        jQuery.post('helpers/users/addUser.php', { name: name, pass: pass })
            .done(function (data) {
                if (data !== '')
                    alertify.alert(data);
                else
                    //Reload the page
                    location.reload();
            })
            .fail(catchFail);
    }

    // Delete a user from the db, based on id
    function deleteUser(userId) {
        jQuery.post('helpers/users/deleteUser.php', { id: userId })
            .done(function (data) {
                if (data !== '')
                    alertify.alert(data);
                else
                    //Reload the page
                    location.reload();
            })
            .fail(catchFail);
    }

    // Index page statistics
    function getSummaryStats(domain, cb) {
        jQuery.post('helpers/statistics/getSummaryStats.php', { domain: domain })
            .then(cb)
            .fail(catchFail);
    }

    // Return public aliases
    return {
        getPages: getPages,
        populateClientsList: populateClientsList,
        loadHeatmapData: loadHeatmapData,
        getResolutions: getResolutions,
        limitRecordNumber: limitRecordNumber,
        setRecordLimit: setRecordLimit,
        getRecordLimit: getRecordLimit,
        getRecord: getRecord,
        getRecordList: getRecordList,
        getNextRecord: getNextRecord,
        setRecordingWatched: setRecordingWatched,
        deleteClient: deleteClient,
        cleanDataForDomain: cleanDataForDomain,
        deleteZeroRecords: deleteZeroRecords,
        deleteRecord: deleteRecord,
        getUsersList: getUsersList,
        setUserData: setUserData,
        changeUserAccess: changeUserAccess,
        addUser: addUser,
        deleteUser: deleteUser,
        addTag: addTag,
        removeTag: removeTag,
        getShareToken: getShareToken,
        getSummaryStats: getSummaryStats,
        getUtcOffset: getUtcOffset,
        addCountryFlag: addCountryFlag
    };
}());
