$(document).ready(function() {
    function cloneInput() {
        var inc = ++$(".alexa-domain").length;
        if(inc > 5) {
            return false;
        }
        var cloned = $('.alexa-domain:first').clone();
        cloned.find(".control-label").attr("for", "domain" + inc);
        cloned.find(".domain_input").attr("id", "domain" + inc).attr("value", "");
        var removeDomain = cloned.find(".remove_domain");
        removeDomain.show();
        removeDomain.on("click", removeInput);
        cloned.find(".add_domain").on("click", appendInput);
        return cloned;
    }

    function removeInput() {
        $(this).closest(".alexa-domain").remove();
    }

    function appendInput() {
        $(".domain-wrapper").append(cloneInput());
    }
    $(".add_domain").on("click", appendInput);
    $(".remove_domain").on("click", removeInput);
});