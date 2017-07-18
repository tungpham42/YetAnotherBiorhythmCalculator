define(
[
    './EventBus',
    'underscore'
],

function(eventBus){
    'use strict';

    function $(id) { return document.getElementById(id); }

    var uuid = 0,
        defaultOptions = {
            initialScore: 0,
            every: Infinity,
            deduct: 0
        };

    function Score(options) {
        this.setOptions(options);
        this.e_clock = $(this.options.timer);
        this.e_point = $(this.options.score);
        this.channel = eventBus.channel(this.options.uid);
        
        if (this.e_point) {
            this.layout = this.e_point.innerHTML || '%d points';
        }

        this.channel.on('options.change', _.bind(this.setOptions, this));
        
        this.restart();
    }

    var proto = Score.prototype;

    proto.setOptions = function(options) {
        this.options = _.clone(_.defaults(options||{}, defaultOptions));
        this.options.every = this.options.every * 1000 || Infinity;
        if (_.isNumber(options.maxTime) && options.maxTime > 0)
            this.options.maxTime = options.maxTime * 60 * 1000;
        else
            this.options.maxTime = Infinity;
    };
        
    proto.now = function() { return new Date().getTime(); };
        
    proto.restart = function() {
        this.start = this.stopTime = this.last = this.now();
        this.points = _.isNumber(this.options.initialScore) ? this.options.initialScore : 0;
        this.updatePoints();
        this.startTime();
    };
        
    proto.updatePoints = function(){
        if (this.e_point)
            this.e_point.innerHTML = this.layout.replace('%d', this.points);
        
        this.channel.emit('score.change', this.points);
    };
        
    proto.startTime = function() {
        var self = this;
        this.uuid = uuid;
        
        ;(function F(){
            if (self.uuid !== uuid)
                return;

            var now = self.stopTime = self.now();

            if (self.e_clock)
                self.e_clock.innerHTML = self.time();

            if (now - self.last > self.options.every) {
                self.last = now;
                self.scoreDown(-self.options.deduct);
            }

            if (now - self.start >= self.options.maxTime) {
                self.channel.emit('time.finish');
            }

            setTimeout(F, 1000);
        }());
    };
        
    proto.stop = function() { ++uuid; };
        
    proto.scoreUp = function(n) {
        if (_.isNumber(n) && n > 0) {
            this.points += n;
            this.updatePoints();
        }
    };

    proto.scoreDown = function(n) {
        if (_.isNumber(n) && n < 0) {
            this.points += n;
            this.updatePoints();
        }
    };

    proto.getScore = function() { return this.points; };

    proto.time = function() {
        var max = this.options.maxTime,
            _t  = (this.stopTime - this.start),
            time = max > 0 && max < Infinity ? max - _t : _t,
            ms = time % 1000,
            t = ~~(time/1000),
            s = t%60,
            m = ~~(t/60),
            h = ~~(m/60);
            m %= 60;

        return (h > 9 ? h : '0' + h) + ':' +
               (m > 9 ? m : '0' + m%60) + ':' +
               (s > 9 ? s : '0' + s);
    };

    return Score;
});
