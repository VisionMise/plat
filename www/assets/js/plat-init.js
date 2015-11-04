/** Init APIS **/
var API 	= new platAPI();
var gsAPI	= new platAPI('guestServices');
var working = false;

$(document).on('ready', function(event) {
	register_handlers();
	init_home_page();	
});