<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?=__('Translations')?></h1>
    <p><?=__('Translations files available in the system.')?></p>

    <a class="btn btn-warnind pull-right" href="<?=Route::url('default',array('controller'=>'translations','action'=>'index'))?>?parse=1" >
        <?=__('Scan')?></a>

</div>

<table class="table table-bordered">
    <tr>
        <th><?=__('Language')?></th>
    </tr>
<?foreach ($languages as $language):?>
    <tr>
        <td><?=$language->language_name?></td>
        <td width="5%">
            
            <a class="btn btn-warning" 
                href="<?=Route::url('default', array('controller'=>'translations','action'=>'edit','id'=>$language->locale))?>" 
                rel"tooltip" title="<?=__('Edit')?>">
                <i class="icon-pencil icon-white"></i>
            </a>

        </td>
        
    </tr>
<?endforeach?>
</table>