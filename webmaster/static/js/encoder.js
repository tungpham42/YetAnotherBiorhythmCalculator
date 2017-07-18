var Encoder = {
	toUnicode: function(source) {
		var result = '';
		for (i=0; i<source.length; i++) {
			result += '&#' + source.charCodeAt(i);
		}
		return result;
	},

	toHexHTML: function(source) {
		var hexhtml = '';
		for (i=0;i<source.length;i++) {
			if (source.charCodeAt(i).toString(16).toUpperCase().length < 2) {
				hexhtml += "&#x0" + source.charCodeAt(i).toString(16).toUpperCase() + ";";
			} else {
				hexhtml += "&#x" + source.charCodeAt(i).toString(16).toUpperCase() + ";";
			}
		}
		return hexhtml;
	},
}