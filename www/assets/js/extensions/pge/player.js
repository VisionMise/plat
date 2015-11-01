var playerAPI = function() {

	this.api 		= {};

	this.init 		= function() {
		this.api 	= new platAPI('player');
	};

	this.move 		= function(x, y) {
		this.api.call('moveTo', [x, y], function(data) {
			if (data == true) gsAPI.page('home', {}, '#content', function(event) {});
		});
	};

	return this.init();
};