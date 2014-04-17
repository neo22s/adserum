$(function () {

	var rules = {
		    	rules: {
                    name: {
                        minlength: 2,
                        maxlength: 145,
                        required: true
                    },

                    email: {
                        minlength: 2,
                        maxlength: 145,
                        email: true,
                        required: true
                    },

					title: {
						minlength: 2,
						maxlength: 25,
						required: true
					},
					desc: {
						minlength: 2,
						maxlength: 35,
						required: true
					},
					desc2: {
						minlength: 2,
						maxlength: 35,
						required: true
					},
					url: {
						minlength: 10,
						maxlength: 300,
						required: true
					},
					durl: {
						minlength: 5,
						maxlength: 35,
						required: true
					},
					lang: {
						required: true
					},
                    terms: {
                        required: true
                    },
				}
		    };
		
	    var validationObj = $.extend (rules, Theme.validationRules);
	    
		$('form').validate(validationObj);
});


$(function() {
	
	var location = {};
	var locationLabels = [];
	var searchLocation = _debounce(function(query, process)
	{
		$.get( $('#city').data('source'), { s: query, countries: $('#countries').val() }, function ( data ) 
		{
			location = {};
			locationLabels = [];
			data = $.parseJSON(data);
			for (var item in data) 
			{
				location[ data[item].label ] = {
					label: data[item].label,
					id: data[item].id,
				}
				locationLabels.push( data[item].label );
    		}    
			process( locationLabels );
		});
	},300);

	$( "#city" ).typeahead({
		matcher: function () { return true; },
		source: function ( query, process ) {
			searchLocation( query, process);
		},
		updater: function (item)
		{
			var option = $("<option />").val(location[ item ].id ).append(location[ item ].label).attr('selected', 'selected');
            $('#cities').append(option).trigger("liszt:updated");
			//return item;
		},
		items: 20,
		minLength: 3,
	});

});