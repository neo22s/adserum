<script type="text/javascript">
if (typeof geoip_city!="function")document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://j.maxmind.com/app/geoip.js\"></scr"+"ipt>");
document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://api.adserum.<?=Core::config('common.tld')?>/sync.js?a=<?=$user->id_user?>&f=<?=$f->id_adformat?>&w=<?=$f->width?>&h=<?=$f->height?>\"></scr"+"ipt>");
</script>