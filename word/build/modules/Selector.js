define(
[
    './Utils',
    'EventEmitter'
],

function($) {
    'use strict';

    function Position() {
        this.x   = -1;
        this.y   = -1;
        this.col = -1;
        this.row = -1;
    }
    
    function isSameCell(cell1, cell2) {
        return cell1 && cell2 && cell1.row == cell2.row && cell1.col == cell2.col;
    }

    function Selector(element, cellSize) {
        this.element  = element;
        this.cellSize = cellSize;

        $.on(element, 'mousedown', _.bind(onmousedown, this));
        $.on(element, 'mousemove', _.bind(onmousemove, this));
        $.on(element, 'mouseup',   _.bind(onmouseup, this));
    }

    EventEmitter.mixin(Selector);

    var proto = Selector.prototype;

    proto.setCellSize = function(cellSize) {
        this.cellSize = cellSize;
    };

    proto.getPosition = function(event) {
        var position = new Position;
        // Modificar coordenadas (x,y), sumar y restar 1 en caso que sea 0
        position.x = (((event.pageX - this.pos.x) + 1) || event.offsetX + 1) - 1;
        position.y = (((event.pageY - this.pos.y) + 1) || event.offsetY + 1) - 1;

        position.col = ~~(position.x / this.cellSize);
        position.row = ~~(position.y / this.cellSize);
        
        return position;
    };

    function getEvent(event) {
        return event.touches && event.touches.length ? event.touches[0] : event;
    }

    /* Event handlers */
    function onmousedown(event) {
        stopAndPrevent(event);
        event = getEvent(event);

        this.isMousedown = true;

        this.pos = $.position(this.element);

        var start = this.getPosition(event);

        if (this.start && !isSameCell(start, this.start)) {
            this.emit('stop', this.start, start);
        }
        
        this.emit('clear');
        this.emit('start', start);
        this.start = start;
    }

    function onmouseup(event) {
        stopAndPrevent(event);
        this.isMousedown = false;

        if (!this.mousemove)
            return;

        this.mousemove = false;

        event = getEvent(event);

        this.emit('stop', this.start, this.end);
        this.start = this.end;
    }

    function onmousemove(event) {
        stopAndPrevent(event);
        if (!this.isMousedown) return;

        this.mousemove = true;

        event = getEvent(event);

        this.end = this.getPosition(event);
        this.emit('move', this.start, this.end);
    }
    
    function stopAndPrevent(event) {
        event.stopPropagation();
        event.preventDefault();
    }

    return Selector;
});	
