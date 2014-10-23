jQuery(function($){
	$( '#brasa_slider_sortable' ).sortable();
	$('#search-bt-slider').click(function(){
		var url = $('#brasa_slider_result').attr('data-url');
		var key = $('#search_brasa_slider').val();
		$('#brasa_slider_result').html('Buscando...');
		$.get( url + '?brasa_slider_ajax=true&key=' + key, function( data ) {
			$('#brasa_slider_result').html( data );
		});
	});
	$(document).on('click','#brasa_slider_result .brasa_slider_item',function(e){
		var html = $(this).html();
		$('#brasa_slider_sortable').append('<li class="brasa_slider_item">'+html+'</li>');
		$('#brasa_slider_result').html('');
	});
});
