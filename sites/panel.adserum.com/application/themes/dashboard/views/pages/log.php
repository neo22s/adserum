<div class="row">
    <div class="span12">
        <h1><?=__('Logs')?></h1>
        
        <form>
        
        </form>


        <form id="edit-profile" class="form-inline" method="post" action="">
            <fieldset>
                <select name='id_site' id='id_site' onChange="reload();">
                    <?foreach ($sites as $site):?>
                    <option value="<?=$site?>"><?=$site?></option>
                    <?endforeach?>
                </select>

                KO Log date <input  type="text" class="span2" size="16" id="ko_date" name="ko_date"  value="<?=date('Y-m-d')?>" data-date-format="yyyy-mm-dd">
                <a href="#" class="btn btn-primary" onclick="reload();">Reload</a>
            </fieldset>
        </form>


        <fieldset id="zone">            
            <h3><?=__('Log Apache'); ?></h3>
            <iframe id="log_apache" src="<?=Route::url('default',array('controller'=>'log','action'=>'apache','id'=>$default_apache))?>/" width="100%" height="300"></iframe>
        </fieldset>
         <fieldset id="zone2">
            <h3><?=__('Log KO'); ?></h3>
            
            <iframe id="log_ko" src="<?=Route::url('default',array('controller'=>'log','action'=>'ko','id'=>$default_ko))?>/" width="100%" height="300"></iframe>
        </fieldset>
    </div>
</div>

<script type="text/javascript">
function reload()
{
    date = document.getElementById('ko_date').value;
    site = document.getElementById('id_site').value;
    document.getElementById('log_apache').src = "<?=Route::url('default',array('controller'=>'log','action'=>'apache'))?>/"+site;
    document.getElementById('log_ko').src = "<?=Route::url('default',array('controller'=>'log','action'=>'ko'))?>/"+site+'*'+date;
}
</script>