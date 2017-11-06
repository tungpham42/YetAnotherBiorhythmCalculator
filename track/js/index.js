/* global indexStatistics, UST, userTrackAdminPanel */
'use strict';

(function ($) {
    try {
        // Check browser support
        // Arow functions 
        eval('let x = arr => arr');
        // Default parameters
        eval('((def=0) => { if(def !== 0) throw new Error("ES6 Default parameters not supported");})()');
    } catch(e) {
        // eslint-disable-next-line no-alert
        alert('Your browser does not support some ES6 features. Please use the latest version of Google Chrome.');
        return;
    }

    $(function () {
        // Script path
        var loc = document.location.href;
        if (loc.indexOf("index.html" !== -1)) {
            loc = loc.replace("index.html", "");
        }
        loc += "tracker.js";

        // Remove preceding hash if existant
        loc = loc.replace("#tracker.js", "tracker.js");
        $("#scriptPath").val('<script src="' + loc + '"></script>');
    
        // Auto-select text on click
        $("#scriptPath").click(function () {
            $(this).select();
        });

        // Admin bar
        $('#adminName').text($.cookie('userTrackUsername'));
   
        // Hide stuff basic users should not see
        var basicUser = $.cookie('userTrackUserLevel') < 1;
        if (basicUser) {
            $('.adminOnly').hide();
        }
        
        // Load domains
        $.getJSON('helpers/getDomains.php', function (data) {

            if (data.length === 0) {
                $('#startGuide').slideDown(300);
            } else {
                $('#adminPanelWrap').append("<hr /><center><h1 style='text-decoration:none;'>Tracker.js include path</h1>" +
                    "Note that jQuery must also be included before this.</center>");
                $('#scriptPath').appendTo('#adminPanelWrap');
            }

            // Display all domains stored, including thumbnail
            for (var v in data) {
                var d = $('<div class="domain"></div>');
                var thumb = $('<div class="thumbnail"></div>');
                var a = $('<a href="#"></a>');
                var thumbnailAPI = 'https://thumbapi.tips4design.com/thumb';
                a.append("<img src='" + thumbnailAPI + "?sw=1000&url=" + data[v].domain + "' title='Website thumbnail.'/>");
                a.append("<span class='domainName'>" + data[v].domain + "</span>");
                thumb.append(a);
                thumb.append("<span class='pageViews'>"
                                    + data[v].count + " recorded visitor" + (+data[v].count !== 1 ? 's' : '')
                                + "</span>");
                thumb.append("<br /><span class='recordLimit' title='Click to edit'>Visitors limit...</span>");
                
                var statistics = $('<div class="statistics"></div>');
                var tabs = $('<div class="statistics_tabs">' +
                                '<div class="selected summaryStatistics" data-action="showSummary">Summary</div>' +
                                '<div class="graph pageViewsStatistics">Page views</div>' +
                                '<div class="graph visitorsStatistics">Distinct visitors </div>' +
                             '</div>');

                statistics.append(tabs);
                d.append(thumb);
                d.append(statistics);

                $('#domainWrap').append(d);

                // Get the statistics for the current domain
                indexStatistics.showSummary(d, true);

                //Ajax call to display the record limit
                UST.API.getRecordLimit(data[v].domain, displayLimit, v);
            }

            //Save current user and add panel events/*
            $.getJSON('helpers/users/getUser.php', function (data) {
                userTrackAdminPanel.setUser(data);
                userTrackAdminPanel.setPanel($('#adminPanel'));
                userTrackAdminPanel.bindEvents();
                userTrackAdminPanel.getUserList();
            }).fail(function (data) {
                console.error(data);
            });

            //Display number of domains tracked
            var nr = $('.domain').length;
            $('#trackingNumber').text(nr + ' domain' + (nr > 1 ? 's' : ''));

            //Display number of total page views
            nr = 0;
            $('.domain span.pageViews').each(function () {
                nr += parseInt($(this).text());
            });
            $('#viewNumber').text(nr);

            // New tooltips
            setTimeout(function () {
                $(".recordLimit, .changePassword img").qtip({
                    content: {
                        attr: 'title'
                    },
                    style: {
                        classes: 'qtip-rounded qtip-red tooltip'
                    },
                    position: {
                        target: 'mouse',
                        adjust: {
                            y: 20,
                            x: 20
                        },
                        viewport: jQuery(window)
                    }
                });
            }, 1000);
        });

        function displayLimit (elIndex, limit) {
            $('.recordLimit').eq(elIndex).html('Recording latest <b class="val">' + limit + '</b> visitors');
        }
    
        // Change domain
        $(document).on('click', '.thumbnail', function () {

            // Get new domain value, from span title
            localStorage.domain = $(this).find('span:first').text();

            // Redirect to userTrack.html page
            window.location = 'userTrack.html';
        });

        // Ability to change record limit
        $(document).on('click', '.recordLimit', function (e) {

            // Only admins can set a limit
            if ($.cookie('userTrackUserLevel') !== '5') {
                alertify.alert("You do not have admin rights!");
                return;
            }

            // So we are not redirected to domain page
            e.stopPropagation();

            // Promt user to insert new limit
            var t = $(this);
            var domain = t.parent().find('.domainName').text();

            alertify.defaultValue($('.val', t).text())
                .prompt("New limit?", function (limit) {
                    if (+limit > 0) {         
                        UST.API.setRecordLimit(domain, limit);
                        $('.val', t).text(limit);
                    } else alertify.error("Value has to be a positive integer");
                });

            return false;
        });

        $(document).on('click', '.statistics_tabs div', function () {
            $('div', $(this).parent()).removeClass('selected');
            $(this).addClass('selected');

            var d = $(this).parents('.domain');

            if($(this).hasClass('graph')) {
                indexStatistics.createVisitorsGraph(d, $(this).hasClass('visitorsStatistics'));
            } else {
                indexStatistics[$(this).attr('data-action')](d);
            }
        });
    });
})(jQuery);
