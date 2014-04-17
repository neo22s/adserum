<div class="page-header">
	<h1><?=__('Advertisements')?></h1>
	<a class="btn btn-primary pull-right" href="<?=Route::url('default', array('controller'=> 'ads', 'action'=>'new')) ?>">
		<i class="icon-pencil icon-white"></i>
		<?=__('New')?>
	</a>				
</div>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th><?=__('Title')?></th>
			<th><?=__('Displays')?></th>
			<th><?=__('Displays Left')?></th>
			<th><?=__('Status')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?foreach($ads as $ad):?>
			<tr id="tr<?=$ad->pk()?>">
				<td><?=$ad->title?></td>
				<td><?=$ad->displays?></td>
				<td><?=$ad->displays_left?></td>
				<td><?=Model_Ad::$statuses[$ad->status]?></td>
				<td>
					<? if ($ad->status == Model_Ad::STATUS_INACTIVE)
						{
							$order = $ad->order(Model_Order::STATUS_CREATED);
							if ($order->loaded())
							{
								?>
								<a class="btn btn-primary" href="<?=Route::url('default',array('controller'=>'payment_paypal','action'=>'form','id'=>$order->id_order));?>">
									<i class="icon-shopping-cart"></i> <?=__('Pay')?>
								</a>
								<?
							}
						}
						else
						{
							?><a class="btn btn-primary" href="<?=Route::url('default',array('controller'=>'ads','action'=>'credit','id'=>$ad->id_ad));?>">
							<i class="icon-shopping-cart"></i> <?=__('Renew')?></a><?
						}
						
					?>
					<a class="btn btn-mini" href="<?=Route::url('default',array('controller'=>'ads','action'=>'stats','id'=>$ad->id_ad));?>">
						<i class="icon-align-right"></i> <?=__('Stats')?>
								</a>
					
				</td>
			</tr>
		<?endforeach?>
	</tbody>
</table>

<?=$pagination?>