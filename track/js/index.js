(function ($){
    $(function () {
        loadSettings();

        // Script path
        var loc = document.location.href;
        if (loc.indexOf("index.html" != -1)) {
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
        // Tooltip
        $("*[title]").qtip({
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
                a.append("<img src='http://free.pagepeeker.com/v2/thumbs.php?size=m&url=" + data[v].domain + "' title='Website thumbnail.'/>");
                a.append("<span class='domainName'>" + data[v].domain + "</span>");
                thumb.append(a);
                thumb.append("<span class='pageViews'>"
                                    + data[v].count + " distinct visitor" + (data[v].count != 1 ? 's' : '')
                                + "</span>");
                thumb.append("<br /><span class='recordLimit' title='Click to edit'>Visitors limit...</span>");
                
                var statistics = $('<div class="statistics"></div>');
                var tabs = $('<div class="statistics_tabs">' +
                                '<div class="selected pageViewsStatistics">Page views</div>' +
                                '<div class="visitorsStatistics">Distinct visitors </div>' +
                             '</div>');

                statistics.append(tabs);
                d.append(thumb);
                d.append(statistics);

                $('#domainWrap').append(d);

                // Get the statistics for the current domain
                indexStatistics.createVisitorsGraph(d);

                //Ajax call to display the record limit
                userTrackAjax.getRecordLimit(data[v].domain, displayLimit, v);
            }

            //Save current user and add panel events/*
            $.getJSON('helpers/users/getUser.php', function (data) {
                userTrackAdminPanel.setUser(data);
                userTrackAdminPanel.setPanel($('#adminPanel'));
                userTrackAdminPanel.bindEvents();
                userTrackAdminPanel.getUserList();
            }).fail(function (data) {
                console.log(data);
            });

            //Display number of domains tracked
            var nr = $('.domain').length;
            $('#trackingNumber').text(nr + ' domain' + (nr > 1 ? 's' : ''));

            //Display number of total page views
            nr = 0;
            $('.domain > span.pageViews').each(function () {
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

        function displayLimit(elIndex, limit) {
            $('.recordLimit').eq(elIndex).html('Recording latest <b class="val">' + limit + '</b> visitors');
        }
    
        // Change domain
        $(document).on('click', '.thumbnail',  function () {

            // Get new domain value, from span title
            localStorage.domain = $(this).find('span:first').text();

            // Reset cached url if domain is changed
            localStorage.url = '/';

            // Redirect to userTrack.html page
            window.location = 'userTrack.html';
        });

        // Ability to change record limit
        $(document).on('click', '.recordLimit', function (e) {

            // Only admins can set a limit
            if ($.cookie('userTrackUserLevel') != 5) {
                alert("You do not have admin rights!");
                return;
            }

            // So we are not redirected to domain page
            e.stopPropagation();

            // Promt user to insert new limit
            var t = $(this);
            var domain = t.parent().find('.domainName').text();

            var value = prompt("New limit?", $('.val', t).text() );

            if (value == parseInt(value)) {            
                userTrackAjax.setRecordLimit(domain, value);
                $('.val', t).text(value);
            }

            return false;
        });

        $(document).on('click', '.statistics_tabs div', function () {
            $('div', $(this).parent()).removeClass('selected');
            $(this).addClass('selected');

            var d = $(this).parents('.domain');
            indexStatistics.createVisitorsGraph(d, $(this).hasClass('visitorsStatistics'));
        });

    });
})(jQuery);