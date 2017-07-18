self.addEventListener('message', function(e) {
	var data = e.data;
	self.postMessage(parseInt(data.left,10));
}, false);