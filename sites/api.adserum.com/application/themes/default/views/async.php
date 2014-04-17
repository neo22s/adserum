(function(){if (typeof geoip_city != 'function'){
	var geo = document.createElement('script');geo.type = 'text/javascript';
	xhrObj  = (window.XMLHttpRequest)?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP");
	xhrObj.open('GET','http://j.maxmind.com/app/geoip.js',false);xhrObj.send('');geo.text=xhrObj.responseText;
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(geo, s);
}})();

(function() {
  	var aff  = <?=Core::get('a')?>;
  	var rep  = document.getElementsByClassName("serum_frame").length+1;
  	var name = "serum_frame_"+aff +"_<?=Core::get('f')?>_"+rep;
	var lang = window.navigator.clientLanguage || window.navigator.language;
	var url  = "<?=Route::url('default',array('controller'=>'ads'))?>?aff="+aff +"&rep="+rep
				+"&width=<?=Core::get('w')?>&height=<?=Core::get('h')?>&format=<?=Core::get('f')?>"
				+"&country="+geoip_country_code()+"&city="+geoip_city()+"&lang="+lang
				+"&rnd="+Math.random()+"&csrf_ads=<?=CSRF::token('ads')?>";
	
	var cdiv = document.getElementById("serum_<?=Core::get('id')?>");
    var ifr  = document.createElement("iframe");
		ifr.setAttribute("id", name);
		ifr.setAttribute("name", name);
		ifr.setAttribute("class", "serum_frame");
		ifr.setAttribute("src", url);
		ifr.setAttribute("frameborder", "0");
		ifr.setAttribute("height", <?=Core::get('h')?>);
		ifr.setAttribute("width", <?=Core::get('w')?>);
		ifr.setAttribute("marginheight", "0");
		ifr.setAttribute("marginwidth", "0");
		ifr.setAttribute("scrolling", "no");	
		ifr.setAttribute("allowTransparency", "true");	
   	cdiv.appendChild(ifr);
})();