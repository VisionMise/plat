var playerAPI = function() {

	this.api 		= {};

	this.init 		= function() {
		this.api 	= new platAPI('player');
	};

	this.move 		= function(x, y) {
		if (working == true) return;

		working 	= true;
		this.api.call('moveTo', [x, y], function(data) {
			if (data == true) gsAPI.page('home', {}, '#content', function(event) {
				working	= false;
				register_handlers();
			});
		});
	};

	this.goNorth	= function() {
		if (working == true) return;

		working 	= true;
		this.api.call('move', ['north'], function(data) {
			if (data == true) gsAPI.page('home', {}, '#content', function(event) {
				working	= false;
				register_handlers();
			});
		});
	};

	this.goSouth	= function() {
		if (working == true) return;

		working 	= true;
		this.api.call('move', ['south'], function(data) {
			if (data == true) gsAPI.page('home', {}, '#content', function(event) {
				working	= false;
				register_handlers();
			});
		});
	};

	this.goWest		= function() {
		if (working == true) return;

		working 	= true;
		this.api.call('move', ['west'], function(data) {
			if (data == true) gsAPI.page('home', {}, '#content', function(event) {
				working	= false;
				register_handlers();
			});
		});
	};

	this.goEast		= function() {
		if (working == true) return;

		working 	= true;
		this.api.call('move', ['east'], function(data) {
			if (data == true) gsAPI.page('home', {}, '#content', function(event) {
				working	= false;
				register_handlers();
			});
		});
	};

	this.inventory 	= function() {
		if (working == true) return;

		working 		= true;
		$('.window').not('section.inventory').slideUp(250);
		$('section.inventory').slideToggle(250);
		working			= false;
	};

	this.minimap 	= function() {
		if (working == true) return;

		working 		= true;
		$('.window').not('section.minimap').slideUp(250);
		$('section.minimap').slideToggle(250);
		working			= false;
	};

	this.playerPage 	= function() {
		if (working == true) return;

		working 		= true;
		$('.window').not('section.playerPage').slideUp(250);
		$('section.playerPage').slideToggle(250);
		working			= false;
	};

	this.skills 		= function() {
		if (working == true) return;

		working 		= true;
		$('.window').not('section.skills').slideUp(250);
		$('section.skills').slideToggle(250);
		working			= false;
	};

	this.players 		= function() {
		if (working == true) return;

		working 		= true;
		$('.window').not('section.players').slideUp(250);
		$('section.players').slideToggle(250);
		working			= false;
	};

	return this.init();
};