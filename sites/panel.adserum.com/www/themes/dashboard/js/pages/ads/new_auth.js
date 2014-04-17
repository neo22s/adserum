$(function () {	
	// button publish
	var button_text = $(".price_new_auth").text(); //button text
	var button_text_hidden = $(".price_new_auth_hidden").val(); //hidden input text for payed ads
	$('select[name=product]').on('change',function(){
			var price = $('option:selected', this).attr('data-price');
			if(price > 0)
				price_text = button_text_hidden + " $" + price.replace('.000','');
			else
				price_text = button_text;
			$(".price_new_auth").text(price_text);
			
		});

	// live preview
	$('#title').one('click', function(){
			$('.title').empty();
	  	});
	$('#title').keyup( function(){
	  	$('.title').text($(this).val());
	});
	$('#desc').one('click', function(){
			$('.desc').empty();
	  	});
	$('#desc').keyup( function(){
	  $('.desc').text($(this).val());
	});
	$('#desc2').one('click', function(){
			$('.desc2').empty();
	  	});
	$('#desc2').keyup( function(){
	  $('.desc2').text($(this).val());
	});
	$('#durl').one('click', function(){
			$('.url').empty();
	  	});
	$('#durl').keyup( function(){
	  $('.url').text($(this).val());
	});

});