;(function(){
var table = document.getElementById("words-table");

function addInput(e) {
    e.preventDefault();
    
    var tr = document.createElement("tr");
    tr.innerHTML = '<td><input type="text" class="span3" name="word[]" value=""/></td>' +
                   '<td><input type="text" class="span8" name="description[]" value=""/></td>';
    table.appendChild(tr);
    getLastInputs()[0].focus();
}

function getLastInputs() {
    var tr = table.getElementsByTagName("tr");
    return tr[tr.length-1].getElementsByTagName("input");
}

document.onkeydown = function(e) {
    var elem = e.target || e.srcElement;
    if (!e.shiftKey && getLastInputs()[1] == elem && e.keyCode == 9) {
        addInput(e);
    }
}

document.getElementById("add-input").onclick = addInput;
}());
