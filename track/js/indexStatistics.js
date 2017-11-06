/* global Chart, UST */
'use strict';

/**
 * indexStatistics namespace for displaying graphs on homepage
 * @type {Object}
 */
window.indexStatistics = (function () {

    function Stats () {

        /**
         * Removes the old cacnvas
         * @param  {jQuery} domainDiv [description]
         */
        this.removeGraph = function (domainDiv) {
            var statistics = jQuery('.statistics', domainDiv);

            var tabs = jQuery('.statistics_tabs', domainDiv).detach();
            statistics.empty().append(tabs);

            var message = jQuery('<span class="message">Loading statistics...</span>');
            statistics.prepend(message);
        };

        /**
         * Shows the summary stats for the given domain div.
         * @param  {jQuery} domainDiv     The domain div to show the stats for.
         * @param  {Boolean} [recurseUpdate=true] Whether to recursively call this function 
         */
        this.showSummary = function (domainDiv, recurseUpdate) {
            // If tab is not selected, do nothing
            if(!jQuery('.summaryStatistics', domainDiv).hasClass('selected')) return;

            if(typeof recurseUpdate === 'undefined') recurseUpdate = true;
            var $stats = jQuery('.summaryStats', domainDiv);

            // If we changed the tab or it's the first time we load the tab
            if($stats.length === 0) this.removeGraph(domainDiv);

            var statistics = jQuery('.statistics', domainDiv);
            var message = jQuery('.message', domainDiv);
            
            UST.API.getSummaryStats(jQuery('.domainName', domainDiv).text(), function (stats) {
                stats = JSON.parse(stats);
                message.remove();

                // Show direct referral
                stats.referrers = stats.referrers.map(function (el) {
                    if (!el.url) {
                        el.url = '#';
                        el.name = 'direct';
                    } else {
                        el.name = el.url.match(/^(?:https?:\/\/)?(?:[^@\/\n]+@)?(?:www\.)?([^:\/\n]+)/im)[1];
                    }
                    
                    return el;
                });

                if($stats.length === 0) {
                    var countDivs = jQuery('#summaryStatsTemplate').tmpl(stats);
                    statistics.prepend(countDivs);
                } else {
                    // The template is already visible, just update the values
                    var tmpl = $stats.tmplItem();
                    tmpl.data = stats;
                    tmpl.update();
                }
            });

            var _this = this;
            if(recurseUpdate) {
                setTimeout(function () {
                    _this.showSummary(domainDiv, true);
                }, 5000);
            }
        };

        this.createVisitorsGraph = function (domainDiv, graphSource) {

            this.removeGraph(domainDiv);

            var statistics = jQuery('.statistics', domainDiv);
            var message = jQuery('.message', domainDiv);
            var canvas = jQuery('<canvas width="' + statistics.width() + 
                                    '"height="' + statistics.height() + '"></canvas>');
            statistics.append(canvas);

            var ctx = canvas.get(0).getContext("2d");

            // Get AJAX url based on the source of the required data (pageviews/visitors)
            var url = 'getPageviewsGraph.php';
            
            if(graphSource === true)
                url = 'getVisitorsGraph.php';

            jQuery.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'helpers/statistics/' + url,
                data: {domain: jQuery('.domainName', domainDiv).text()}
            })
            .done(function (res) {
                var data = {
                    labels: [],
                    datasets: [
                        {
                            label: "Visits",
                            fillColor: "rgba(220,220,220,0.2)",
                            strokeColor: "rgba(220,220,220,1)",
                            pointColor: "rgba(220,220,220,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: []
                        }
                    ]
                };

                for(var idx in res) {
                    data.labels.push(res[idx][0]);
                    data.datasets[0].data.push(res[idx][1]);
                }
                
                var options = {
                    pointHitDetectionRadius: 3
                };

                var chart = new Chart(ctx).Line(data, options);
                chart.update();
                
                if(res.length)
                    message.remove();
                else {
                    message.text('There is no data to display.');
                }
            })
            .fail(function (err) {
                message.text('Something went wrong. Check the console.');
                console.log(err);
            });
        };
    }

    return new Stats();
})();
