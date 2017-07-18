self.addEventListener('message', function(e) {
	var data = e.data;
	self.postMessage(Math.sqrt(Math.pow(data.velocity,2) + Math.sqrt(2*data.acceleration*data.distance)));
}, false);