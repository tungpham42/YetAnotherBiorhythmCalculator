self.addEventListener('message', function(e) {
	var data = e.data;
	switch (data.command) {
		case 'velocity':
			self.postMessage(Math.sqrt(Math.pow(data.velocity,2) + Math.sqrt(2*data.acceleration*data.distance)));
			break;
		case 'distance':
			self.postMessage(parseInt(data.left,10));
			break;
		case 'time':
			self.postMessage(data.distance/data.velocity);
			break;
	};
}, false);