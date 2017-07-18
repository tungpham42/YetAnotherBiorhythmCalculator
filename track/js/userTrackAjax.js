// Global DEBUG variable
var DEBUG = false;

/***
 Ajax requests sent to the server
***/
var userTrackAjax = (function () {

    //In case anything goes wrong
    function catchFail() {
        alert("Something went wrong on the server-side. Please try again!");
    }
    //Display list of resolutions window sizes available in db
    function getResolutions() {

        if (options.url === undefined) {
            alert('No pages saved in the database. Database may be empty.');
            return;
        }

        jQuery.ajax({
            type: 'POST',
            dataType: "json",
            url: 'helpers/getResolutions.php',
            data: { url: options.url, domain: options.domain },
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/j-son;charset=UTF-8");
                }
            },
            success: function (data) {
                DEBUG && console.log('resolutions', data);

                jQuery('#resolution').html('<option value="-1" selected>Any</option>');
                for (var v in data)
                    jQuery('#resolution').append('<option value="' + data[v][0] + '">' + data[v][0].split(' ')[0] + ' x ' + data[v][0].split(' ')[1] + '</option>');
                if (artificialTrigger) {
                    jQuery('#resolution option[value="' + options.resolution + '"]').trigger('change');
                }

                //If we're not in record-playback mode, we only display the width
                if (options.what != 'record') {
                    jQuery('#resolution option').each(function () {

                        //Remove all options with the same width
                        var curEl = jQuery(this);
                        var curWidth = curEl.val().split(' ')[0];

                        //Continue if resolution is set to "Any" or is removed
                        if (curWidth < 0)
                            return 1;

                        //Don't display resolution, but width
                        curEl.text(curWidth);

                        var index = curEl.index();

                        for (var i = index + 1; i < jQuery('#resolution option').length ; ++i) {

                            var el = jQuery('#resolution option').eq(i);

                            if (el.val().split(' ')[0] == curWidth)
                                jQuery('#resolution option').eq(i).val('-2');
                        }

                        jQuery('#resolution option[value="-2"]').remove();
                    });
                }
            },
            error: function (data) {
                alert(data.responseText);
            }

        });
    }

    // Display list of pages available in db
    function getPages() {
        jQuery.ajax({
            type: 'POST',
            dataType: "json",
            url: 'helpers/getPages.php',
            data: { domain: options.domain },
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/j-son;charset=UTF-8");
                }
            },
            success: function (data) {

                jQuery('#page').html('');

                for (var v in data)
                    jQuery('#page').append('<option value="' + data[v] + '">' + data[v] + '</option>');

                
                // First page selected if no other defaults will be set
                if(data[0]) jQuery('#page option[value="' + data[0] + '"]').attr('selected', true);

                var defaultUrl = jQuery('#page option:first').val() || '/';

                /*
                 * Don't cache anymore last page.
                if (localStorage.url !== undefined) {
                    defaultUrl = localStorage.url;
                    jQuery('#page option[value="' + defaultUrl + '"]').attr('selected', true);
                }*/

                if (localStorage.what !== undefined) {
                    options.what = localStorage.what;
                    jQuery('.opt').removeClass('selected');
                    jQuery('.opt[data-value=' + options.what + ']').addClass('selected');

                    // If it is recording we have to trigger a click to show the menu
                    if(options.what == 'record')
                        jQuery('.opt.selected').trigger('click');
                }

                jQuery('#loading').text("Loading webpage");

                //Only set absolute path for cross-domain
                var absolutePath = '';
                if (options.domain !== '')
                    absolutePath = '//' + options.domain;
                
                setIframeSource(absolutePath + defaultUrl);
                options.url = defaultUrl;
                jQuery("select,input").uniform();
                jQuery('#page').trigger('change');
            },
            error: function (data) {
                if (data.responseText.indexOf('login') != -1)
                    window.location = 'login.php';
                else
                    alert("Could not load pages list from db." + data.responseText);
            }
        });
    }

    //Delete records that go over the specified limit
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
            success: function (data) {
            },
            error: function (data) {
                alert(data.responseText);
            }
        });
    }

    //Set the max number of recordings saved for this domain
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
            success: function (data) {
            },
            error: function (data) {
                alert(data.responseText);
            }
        });
    }
    
    //Get the max number of recordings saved for this domain
    function getRecordLimit(domain, callback, elIndex) {
        jQuery.post("helpers/getRecordLimit.php", { domain: domain })
                .done(function (data) {
                    callback(elIndex, data);
                })
                .fail(function (data) {
                    alert(data.responseText);
                });
    }

    
    //Get available clients
    function populateClientsList(from) {

        // Filters
        var take = jQuery('#numberFilter select').val();
        var startDate = jQuery('#rangeFilter input[name="from"]').val();
        var endDate = jQuery('#rangeFilter input[name="to"]').val();
        var tagFilters = jQuery('.tagFilterElement .value').map(function() {
            return $(this).text();
        }).get();

        // Auxilliary arrays
        var browserCodes = ['chrome', 'opera', 'msie', 'firefox', 'mozilla', 'safari'];
        var browserNames = ['Google Chrome', 'Opera', 'Internet Explorer', 'Mozilla Firefox', 'Mozilla Firefox', 'Safari'];
        var browserImages = ['chrome.png', 'opera.png', 'ie.png', 'firefox.png', 'firefox.png', 'safari.png'];

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
                order: localStorage.order 
            },
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/j-son;charset=UTF-8");
                }
            },
            success: function (data) {

                jQuery('#recordList table tr:has(td)').remove();

                if (data === null || data.clients.length === 0) {
                    jQuery('#recordList table').append('<tr><td colspan="6"><h3>Database is empty.</h3></td></tr>');
                    return;
                }

                var cnt = data.count;
                data = data.clients;

                for (var v in data) {
                    jQuery('#recordList table').append('<tr data-id="' + data[v].token + '"></tr>');

                    // If ID is null means there are no recordings, set correct number to 0
                    if(data[v].recordid == null)
                        data[v].nr = 0;
                    
                    var n = 0;
                    for (var i in data[v]) {

                        // Do something else at column 7 (buttons column)
                        if (++n > 6)
                            break;

                        var td = jQuery('<td class="' + i + '"></td>');
                        // Use .text instead of .html to prevent XSS
                        td.text(data[v][i]);
                        jQuery('#recordList table tr:last').append(td);

                        switch (i) {

                            // Wrap tags in spans
                            case 'tags':
                                td.html('<span class="plus addTag" data-id ="' + data[v].clientid + '">+</span>');
                                td.attr('title', 'Right click a tag to remove it');
                                var tags = data[v][i];
                                for(var idx in tags) {
                                    var span = jQuery('<span class="tag"></span>');
                                    span.text(tags[idx]);
                                    td.append(span);
                                }
                            break;

                            //Add placeholder for flag
                            case 'ip':

                                var newIP = data[v].ip;

                                if (censorIP) {
                                    newIP = data[v].ip.slice(0, -3) + '***';
                                }

                                // By default show the N/A flag
                                td.html('<img src="images/flags/xx.png"/> ' + newIP);

                                // Check first if flag image is cached
                                if (localStorage['c' + data[v].ip] !== undefined) {
                                    td.html('<img src="images/flags/' + localStorage['c' + data[v].ip] + '.png"/> ' + newIP);
                                }
                                else {
                                    // Timeout delay so that the flag api doesn't break
                                    (function (ip, td, newIP) {
                                        setTimeout(function () { addCountryFlag(ip, td, newIP); }, 300 * v);
                                    })(data[v].ip, td, newIP);
                                }

                            break;

                            // Show number of records for client
                            case 'pageHistory':

                                td.empty();
                                td.append(data[v].nr + ' page' + (data[v].nr != 1 ? 's' : ''));

                                // Compute the time spent in hours, minutes, seconds
                                var timeSpentString = '';
                                var ts = data[v].timeSpent;
                                if(ts > 3600) {
                                    var hours = ts/3600 | 0;
                                    timeSpentString += hours + 'h ';
                                    ts -= hours * 3600;
                                }

                                if(ts > 60) {
                                    var mins = ts/60 | 0;
                                    timeSpentString += mins + 'm ';
                                    ts -= mins * 60;
                                }

                                timeSpentString += ts + 's';

                                td.append(' in ' + timeSpentString + ':');

                                var pageList = data[v][i].split(' ');

                                for(var index in pageList) {

                                    var pageName = pageList[index];

                                    // Remove page extension
                                    // Consider that extensions are max 5 characters
                                    var dotPosition = pageName.lastIndexOf('.');
                                    if(dotPosition != -1 && pageName.length - dotPosition - 1 < 5) {
                                        pageName = pageName.slice(0, pageName.lastIndexOf('.'));
                                    }

                                    pageList[index] = '<div class="pageEntry">' + pageName + '</div>';
                                    td.append(pageList[index]);
                                }

                                td.attr('width', '50%');
                            break;
                        }
                    }

                    // Disable the button if it has 0 recordings
                    var disabled = data[v].nr == 0 ? ' disabled title="No movements were recorded. Client may have left immediately." ' : '';

                    // Get first page visited
                    var firstPage = data[v].pageHistory;
                    if(data[v].pageHistory.indexOf(' ') != -1) {
                        firstPage = data[v].pageHistory.split(' ')[0];
                    }

                    // Display the play button
                    jQuery('#recordList table tr:last').append('<td class="playButton"><button ' +
                                            'data-recordid="' + data[v].recordid + '" ' +
                                            'data-page="' + firstPage + '" ' +
                                            'data-resolution="' + data[v].resolution + '" ' +
                                             disabled +
                                            '>Play</button></td>');

                    // Display browser icon
                    var browserEl = jQuery('#recordList table tr:last td.browser').get(0);
                    var br = browserEl.innerText;
                    var browserVersion = br.split(' ').pop();
                    for(var name in browserCodes) {
                        var code = browserCodes[name];
                        if(br.indexOf(code) != -1) {
                            var d = '<img src="images/icons/' + browserImages[name] + '" title="' + browserNames[name] + ' ' + browserVersion + '"/>';
                            d += '<span>' + browserVersion +'</span>';

                            browserEl.innerHTML = d;
                            break;
                        }
                    }
                }

                // Display pagination
                jQuery('#pagination').html('');
                if (cnt) {
                    var totalPages =  cnt / take + (cnt % take != 0);
                    var currentPage = from / take + 1;

                    // Display each page, also select the current page
                    for (var i = 1; i <= totalPages; ++i) {
                        var selected = i == currentPage ? "selected" : "";
                        jQuery('#pagination').append('<span class="' + selected + '">' + i + '</span>');
                    }
                }

                bindClickToList();
            },
            error: function (data) {
                alert(data.responseText);
                console.log(data.responseText);
            }
        });
    }

    //Get country name by ip
    function addCountryFlag(ip, td, newIP) {

        jQuery.ajax({
            type: 'POST',
            url: 'helpers/getCountry.php',
            data: { ip: ip },
            success: function (data) {
                if (data.length == 2) {
                    td.html('<img src="images/flags/' + data.toLowerCase() + '.png"/> ' + newIP);
                    if (data.toLowerCase() != 'xx')
                        localStorage.setItem('c' + ip, data.toLowerCase());
                }
            },
            error: function (data) {
            }
        });
    }

    // Get next record after record with ID = id
    function getNextRecord(id) {
        jQuery.ajax({
            type: 'POST',
            dataType: "json",
            url: 'helpers/getNextRecord.php',
            data: {
                id: id, 
                domain: options.domain 
            },
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/j-son;charset=UTF-8");
                }
            },
            success: function (data) {
                
                DEBUG && console.log("getNextRecord", data);

                data.id = Number(data.id);
                userTrackRecord.setNext(data);
            },
            error: function (data) {
                alert(data.responseText);
            }
        });
    }

    // Load record data from recordID
    function getRecord(id) {

        options.lastid = id;

        jQuery.ajax({
            type: "POST",
            dataType: "json",
            data: { recordid: id, page: options.url, resolution: options.resolution, what: options.what },
            url: 'getData.php',
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/j-son;charset=UTF-8");
                }
            },
            success: function (data) {
                userTrackRecord.setCurrent(data);
            },
            error: function (data) {
                alert("Could not load data!" + data.responseText);
            }
        });
    }

    // Load all recorded pages based on client token
    function getRecordList(token) {
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            data: { token: token },
            url: 'helpers/getRecordList.php',
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/j-son;charset=UTF-8");
                }
            },
            success: function (data) {
                userTrackRecord.setRecordList(data);
            },
            error: function (data) {
                alert("Could not load data!" + data.responseText);
            }
        });
    }

    function loadHeatmapData() {
        DEBUG && console.log("loadHeatmapData");

        jQuery.ajax({
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
            },
            success: function (data) {
                userTrackHeatmap.setData(data);
            },
            error: function (data) {
                console.log(data);
                if (data.responseText.indexOf('login') != -1)
                    window.location = 'login.php';
                else
                    alert("Could not load heatmap data." + data.responseText);
            }
        });
    }

    function addTag(clientID, tag, cb) {
        if(typeof tag === 'undefined' || tag.length === 0) {
          console.log("Tag cannot be empty!");
          return 0;
        }

        jQuery.post('addTag.php', {
                clientID: clientID,
                tagContent: tag
            })
            .done(function (data) {
                if(data !== '') {
                    alertify.error("Tag was not added. You can not have duplicate tags for the same user.");
                    console.log(data);
                    return;    
                }

                DEBUG && console.log('Tag ' + tag + 'added');
                if(typeof cb === 'function') cb();

            })
            .fail(function (data) {
                alertify.error("Something went wrong. Tag was not added.");
                console.log(data.responseText);
            });

        return 1;
    };

    function removeTag(clientID, tag, cb) {
        if(typeof tag === 'undefined' || tag.length === 0) {
          console.log("Tag cannot be empty!");
          return 0;
        }

        jQuery.post('helpers/removeTag.php', {
                clientID: clientID,
                tagContent: tag
            })
            .done(function (data) {
                if(data !== '') {
                    alertify.error("Tag was not removed.");
                    console.log(data);
                    return;    
                }

                DEBUG && console.log('Tag ' + tag + 'removed');
                if(typeof cb === 'function') cb();

            })
            .fail(function (data) {
                alertify.error("Something went wrong. Tag was not removed.");
                console.log(data.responseText);
            });

        return 1;
    };

    // Delete a single record (page) from a client session
    function deleteRecord(id) {

        if (id > 0) {
            jQuery.post('helpers/deleteRecord.php', { recordid: id })
                .done(function (data) {
                    alert('Record deleted!');
                })
                .fail(function (data) {
                    console.log(data);
                    alert("Could not delete record!" + data.responseText);
                });
        }
        else {
            alert('Incorect id format.');
            return 0;
        }
    }

    // Delete client including all data related to it
    function deleteClient(token, el) {
        jQuery.post('helpers/deleteClient.php', { token: token })
            .done(function (data) {
                jQuery(el).slideUp(200, function () { jQuery(this).remove(); });
            })
            .fail(function (data) {
                console.log(data);
                alert("Could not delete client!" + data.responseText);
            });
    }

    function cleanDataForDomain(domain) {
        jQuery.post('helpers/cleanDatabase.php', { domain: domain }, function (data) {
            if (data === '')
                alert('All data stored in the database has been deleted.');
            else alert("Error: " + data);
            window.location.reload();
        });
    }

    function deleteZeroRecords(domain) {
        jQuery.post('helpers/deleteZeroRecords.php', { domain: domain }, function (data) {
            if (data === '')
                alert('Sessions with 0 data have been deleted.');
            else alert("Error: " + data);
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
        if(dataType.indexOf('name') != -1)
            dataType = 'name';

        jQuery.post('helpers/users/setUserData.php', { dataType: dataType, value: value, userId: id })
              .done(function (data) {
                  if (data !== '') {
                      alert(data);
                  } else {
                    if(shouldLogout) {
                        window.location = 'helpers/users/logout.php';
                    }
                  }
              })
              .fail(catchFail);
    }

    // Add or remove domain access for user
    function changeUserAccess(type, domain, userid){
        jQuery.post('helpers/users/changeAccess.php', { type: type, domain: domain, userid: userid })
              .done(function (data) {
                 if (data !== '')
                     alert(data);
                 else
                     //Reload the page
                     location.reload();
              })
              .fail(catchFail);
    }

    // Add new user to the db
    function addUser(name, pass) {
        jQuery.post('helpers/users/addUser.php', { name: name, pass: pass })
            .done(function (data) {
                if (data !== '')
                    alert(data);
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
                    alert(data);
                else
                    //Reload the page
                    location.reload();
            })
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
        removeTag: removeTag
    };

}());