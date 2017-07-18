define(
[
    './UI',
    'text!../assets/template/custom-template.html',
    'i18n!../nls/wordsearch',
    './Utils',
    'underscore'
],

function(UI, tmpl, locale, $) {
    'use strict';
    
    var template = _.template(tmpl);

    function CustomUI(options) {
        
        var div  = document.createElement("div"),
            fragment = document.createDocumentFragment();
        div.innerHTML = template({_: locale});

        for (var i = 0, l = div.children.length; i < l; i++)
            fragment.appendChild(div.children[i].cloneNode(true));

        var oldContainer = document.getElementById(options.container) || document.body;

        if (oldContainer === document.body)
            oldContainer.innerHTML = "";

        $.addClass(oldContainer, "wordsearch");
        oldContainer.appendChild(fragment);
        
        options.container = 'soup';
        
        UI.call(this, options);
    }
    
    CustomUI.prototype = UI.prototype; 
    
    return CustomUI;
});
