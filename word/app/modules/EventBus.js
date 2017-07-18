define(["EventEmitter"], function(){
    var eventBus = new EventEmitter,
        channels = {},
        slice    = [].slice;

    function Channel(uid) {
        this.uid = uid;
    }

    Channel.prototype.on = function(event, fn) {
        eventBus.on(this.uid + event, fn);
    }

    Channel.prototype.emit = function(event) {
        var args = slice.call(arguments);
        args[0] = this.uid + event;
        eventBus.emit.apply(eventBus, args)
    }

    eventBus.channel = function(uid) {
        return channels[uid] = channels[uid] || new Channel(uid);
    }

    return eventBus;
});
