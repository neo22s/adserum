$(function (){
	$('#ko_date').datepicker().on('changeDate', function(ev){
    	reload();
  	});
});
