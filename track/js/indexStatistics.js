/**
 * indexStatistics namespace for displaying graphs on homepage
 * @type {Object}
 */

var indexStatistics = (function () {


    /**
     * Removes the old cacnvas
     * @param  {jQuery} domainDiv [description]
     */
    this.removeGraph = function (domainDiv) {
        jQuery('canvas', domainDiv).remove();

        var message = jQuery('.message', domainDiv);
        if(message.length == 0)
            message = jQuery('<span class="message"></span>');
        
        message.text('Loading graph...');

        jQuery('.statistics', domainDiv).prepend(message);
    };

    this.createVisitorsGraph =  function (domainDiv, graphSource) {

        this.removeGraph(domainDiv);

        var statistics = jQuery('.statistics', domainDiv);
        var message = jQuery('.message', domainDiv);
        var canvas = jQuery('<canvas width="' + statistics.width() + 
                                '"height="' +statistics.height() + '"></canvas>');
        statistics.append(canvas);

        var ctx = canvas.get(0).getContext("2d");

        // Get AJAX url based on the source of the required data (pageviews/visitors)
        var url = 'getPageviewsGraph.php';
        
        if(graphSource == 1)
            url = 'getVisitorsGraph.php';

        jQuery.ajax({
            dataType: 'json',
            type: 'POST',
            url: 'helpers/statistics/' + url,
            data: {domain: jQuery('.domainName', domainDiv).text()},
        })
        .done(function(res) {
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
                pointHitDetectionRadius : 3
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

    return this;
})();