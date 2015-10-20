

var pge_player = function() {

	var self 		= this;
	this.info 		= {};

	this.init 		= function() {

		this.registerHandlers();

		var api 	= new platAPI('player');
		api.call('info', [], function(data) {
			self.info 	= data;
			console.log(self);
		});

		return this;
	};

	this.registerHandlers 	= function() {

		$('.player_perform_changeName').on('click', function(event) {

		});
	};

	this.resetHandlers 		= function() {

	};

	return this.init();
};

var player = new pge_player();