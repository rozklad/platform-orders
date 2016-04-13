$(function(){

	$('[data-async-cart-trigger]').click(function(event){
		event.preventDefault();

		$('body').toggleClass('async-cart-shown');
	});

});