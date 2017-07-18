define(
[
    './Utils',
    'css!./modal.css'
],
function($) {
    'use strict';

    var $modal,
        $msg,
        $close,
        $overlay,
        currentContent;

    function initialize() {
        $modal   = $.$('modal-window');
        $msg     = $.$('modal-window-msg');
        $close   = $.$('modal-window-close');
        $overlay = $.$('modal-window-overlay');

        $.on($close, "click", closeModal);
    }

    function replace(text, tmpl) {
        var i;

        for (i in tmpl)
        {
            if (tmpl.hasOwnProperty(i))
            {
                text = text.replace(new RegExp('{'+i+'}', 'gi'), tmpl[i]);
            }
        }
        
        return text;
    }

    function showModal(id, tmpl) {
        var style = $modal.style,
            elem  = $.$(id);

        elem.className = '';

        if (elem.nodeName == "SCRIPT" || tmpl) {
            $msg.innerHTML = replace(elem.innerHTML, tmpl);
            currentContent = null;
        } else {
            elem.style.display = 'block';
            currentContent = elem;
            $msg.appendChild(elem);
        }

        // Centrar Ventana
        var width = $modal.offsetWidth;

        style.marginLeft = (-width / 2) + 'px';

        // Mostrar
        $modal.className = 'modal ' + id;
        $overlay.className = '';
    }

    function closeModal(e) {
        e && e.preventDefault();
        $modal.className = 'modal hide';
        $overlay.className = 'hide';
        
        var current = currentContent;    
        setTimeout(function() {
            if (!current) {
                $msg.innerHTML = '';
                return;
            }
            current.style.display = 'none';
            document.body.appendChild(current);
        }, 600);

        return false;
    }

    return {
        init: initialize,
        open: showModal,
        close: closeModal
    };
});
