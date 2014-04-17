var uid = Math.round(Math.random()*10000);
document.write("<div id=\"serum_"+uid+"\" style=\"min-width:<?=Core::get('w')?>px;min-height:<?=Core::get('h')?>px;\" ></div>");

function getElementsByClass(searchClass,node,tag) {
    var classElements = new Array();
    if ( node == null )
        node = document;
    if ( tag == null )
        tag = '*';
    var els = node.getElementsByTagName(tag);
    var elsLen = els.length;
    var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
    for (i = 0, j = 0; i < elsLen; i++) {
        if ( pattern.test(els[i].className) ) {
            classElements[j] = els[i];
            j++;
        }
    }
    return classElements;
}


var aff  = <?=Core::get('a')?>;
var rep  = getElementsByClass("serum_frame",document.body,'iframe').length+1;
var name = "serum_frame_"+aff +"_<?=Core::get('f')?>_"+rep;
var lang = window.navigator.clientLanguage || window.navigator.language;
var url  = "<?=Route::url('default',array('controller'=>'ads'))?>?aff="+aff +"&rep="+rep
			+"&width=<?=Core::get('w')?>&height=<?=Core::get('h')?>&format=<?=Core::get('f')?>"
			+"&country="+geoip_country_code()+"&city="+geoip_city()+"&lang="+lang
			+"&rnd="+Math.random()+"&csrf_ads=<?=CSRF::token('ads')?>";

var cdiv = document.getElementById("serum_"+uid);
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
