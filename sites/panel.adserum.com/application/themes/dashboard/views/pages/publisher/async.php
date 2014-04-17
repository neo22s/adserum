<script type="text/javascript">
(function() {var uid = Math.round(Math.random()*10000);
document.write("<div id=\"serum_"+uid+"\" style=\"min-width:<?=$f->width?>px;min-height:<?=$f->height?>px;\" ></div>");
var as= document.createElement("script"); as.type  = "text/javascript"; as.async = true;
as.src= (document.location.protocol == "https:" ? "https" : "http")+ "://api.adserum.<?=Core::config('common.tld')?>/async.js?id="+uid+"&a=<?=$user->id_user?>&f=<?=$f->id_adformat?>&w=<?=$f->width?>&h=<?=$f->height?>";
var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(as, s);})();
</script>