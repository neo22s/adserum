$(function (){
	//Theme.init ();
	$("select").chosen();
});


_debounce = function(func, wait, immediate) {
	var timeout, result;
	return function() {
		var context = this, args = arguments;
		var later = function() {
			timeout = null;
			if (!immediate) result = func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) result = func.apply(context, args);
		return result;
	};
};