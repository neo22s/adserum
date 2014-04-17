<div class="row">

	<div class="span12">
		<h1><?=__('Dashboard')?></h1>

		<form id="edit-profile" class="form-inline" method="post" action="">
			<fieldset>
				<?=__('From')?>
				<input  type="text" class="span2" size="16"
						id="from_date" name="from_date"  value="<?=$from_date?>"  
						data-date="<?=$from_date?>" data-date-format="yyyy-mm-dd">
				<?=__('To')?>
				<input  type="text" class="span2" size="16"
						id="to_date" name="to_date"  value="<?=$to_date?>"  
						data-date="<?=$to_date?>" data-date-format="yyyy-mm-dd">

			<button type="submit" class="btn btn-primary"><?=__('Filter')?></button> 
			
			</fieldset>
		</form>

		<div id="big_stats" class="cf">
			
			<div class="stat">		
				<h4><?=$daily_ads_count?></h4>			
				<?=__('Created ads')?>
			</div> <!-- .stat -->
			
			<div class="stat">				
				<h4><?=$pub_ads_count?></h4>			
				<?=__('Published ads')?>					
			</div> <!-- .stat -->
			
			<div class="stat">			
				<h4><?=$daily_hits_count?></h4>			
				<?=__('Hits')?>						
			</div> <!-- .stat -->
			
			<div class="stat">					
				<h4><?=$daily_clicks_count?></h4>			
				<?=__('Clicks')?>					
			</div> <!-- .stat -->
			
		</div> <!-- /#big_stats -->
		
	</div> <!-- /.span12 -->
	
</div> <!-- /.row -->


<div class="row">
	<div class="span12">
		
		<h3><?=__('Quick view')?></h3>
		<table class="table table-bordered table-striped">
							
			<thead>
				<tr>		
					<th class="span2"></th>						
					<th class="span2"><?=__('Ads')?></th>
					<th class="span2"><?=__('Published')?></th>		
					<th class="span2"><?=__('Hits')?></th>					
					<th class="span2"><?=__('Clicks')?></th>	
					<th class="span2"><?=__('Sales')?></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td class="description"><?=__('Today')?></td>
					<td class="value"><span><?=$today_ads?></span></td>
					<td class="value"><span><?=$today_pads?></span></td>
					<td class="value"><span><?=$today_hits?></span></td>
					<td class="value"><span><?=$today_clicks?></span></td>
					<td class="value"><span><?=$today_ads?></span></td>
				</tr>
				<tr>
					<td class="description"><?=__('Yesterday')?></td>
					<td class="value"><span><?=$yes_ads?></span></td>
					<td class="value"><span><?=$yes_pads?></span></td>
					<td class="value"><span><?=$yes_hits?></span></td>
					<td class="value"><span><?=$yes_clicks?></span></td>
					<td class="value"><span><?=$yes_ads?></span></td>
				</tr>
				<tr>
					<td class="description"><?=__('Month')?></td>
					<td class="value"><span><?=$month_ads?></span></td>
					<td class="value"><span><?=$month_pads?></span></td>
					<td class="value"><span><?=$month_hits?></span></td>
					<td class="value"><span><?=$month_clicks?></span></td>
					<td class="value"><span><?=$month_ads?></span></td>
				</tr>
				<tr>
					<td class="description"><?=__('Year')?></td>
					<td class="value"><span><?=$year_ads?></span></td>
					<td class="value"><span><?=$year_pads?></span></td>
					<td class="value"><span><?=$year_hits?></span></td>
					<td class="value"><span><?=$year_clicks?></span></td>
					<td class="value"><span><?=$year_ads?></span></td>
				</tr>
				<tr>
					<td class="description"><?=__('Total')?></td>
					<td class="value"><span><?=$total_ads?></span></td>
					<td class="value"><span><?=$total_pads?></span></td>
					<td class="value"><span><?=$total_hits?></span></td>
					<td class="value"><span><?=$total_clicks?></span></td>
					<td class="value"><span><?=$total_ads?></span></td>
				</tr>
			</tbody>

		</table>


	</div> <!-- /.span4 -->
</div> <!-- /.row -->


<div class="row">
			
	<div class="span6">
		
		<h3>Ads Created</h3>
		
		<hr />
		
		<?=Chart::column($daily_ads,array('title'=>__('Daily created ads'),'height'=>400,'width'=>600))?>

		
	</div> <!-- /.span6 -->
	
	<div class="span6">
		
		<h3>Ads Published</h3>
		
		<hr />
		
		<?=Chart::column($pub_ads,array('title'=>__('Daily published ads'),'height'=>400,'width'=>600))?>

		
	</div> <!-- /.span6 -->
							
</div> <!-- /row -->


<div class="row">
			
	<div class="span6">
		
		<h3>Hits</h3>
		
		<hr />
		
		<?=Chart::column($daily_hits,array('title'=>__('Daily hits ads'),'height'=>400,'width'=>600))?>

		
	</div> <!-- /.span6 -->
	
	<div class="span6">
		
		<h3>Clicks</h3>
		
		<hr />
		
		<?=Chart::column($daily_clicks,array('title'=>__('Daily clicks ads'),'height'=>400,'width'=>600))?>
		
	</div> <!-- /.span6 -->
							
</div> <!-- /row -->