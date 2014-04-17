<div class="page-header">
	<h1><?=$title?></h1>
	<a class="btn btn-warning pull-right" href="<?=Route::url('default', array('controller'=> 'moderation', 'action'=>'index','id'=>'moderate')) ?>">
		<i class="icon-list icon-white"></i>
		<?=__('Moderate')?>
	</a>
	<a class="btn btn-primary pull-right" href="<?=Route::url('default', array('controller'=> 'moderation', 'action'=>'index','id'=>'published')) ?>">
		<i class="icon-list icon-white"></i>
		<?=__('Published')?>
	</a>
    <a class="btn btn-info pull-right" href="<?=Route::url('default', array('controller'=> 'moderation', 'action'=>'index','id'=>'_all_')) ?>">
        <i class="icon-list icon-white"></i>
        <?=__('All')?>
    </a>
</div>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th><?=__('Title')?></th>
			<th><?=__('Displays')?></th>
			<th><?=__('Displays Left')?></th>
            <th><?=__('Total Displays')?></th>
            <th><?=__('Clicks')?></th>
			<th><?=__('Status')?></th>
			<th><?=__('Action')?></th>
		</tr>
	</thead>
	<tbody>
		<?foreach($ads as $ad):?>
			<tr id="tr<?=$ad->pk()?>">
				<td><?=$ad->title?></td>
				<td><?=$ad->displays?></td>
				<td><?=$ad->displays_left?></td>
                <td><?=$ad->displays-$ad->displays_left?></td>
                <td><?=$ad->count($ad->id_ad.':clicks')?></td>
				<td><?=Model_Ad::$statuses[$ad->status]?></td>
				<td><a class="btn btn-primary btn-small"
						href="<?=Route::url('default',array('controller'=>'moderation','action'=>'edit','id'=>$ad->pk()))?>" >
						<i class="icon-edit"></i>
					</a>
					<a class="btn btn-danger btn-small" onclick="return confirm('<?=__('Sure?')?>');"
						href="<?=Route::url('default',array('controller'=>'moderation','action'=>'delete','id'=>$ad->pk()))?>" >
						<i class="icon-remove"></i>
					</a>
					<a class="btn btn-mini" href="<?=Route::url('default',array('controller'=>'ads','action'=>'stats','id'=>$ad->pk()));?>">
						<i class="icon-align-right"></i> <?=__('Stats')?>
								</a>
				</td>
			</tr>
		<?endforeach?>
	</tbody>
</table>

<?=$pagination?>