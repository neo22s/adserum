
<div class="row">


	<div class="span12">
		<h1><?=__('Stats')?> "<?=$ad->title?>"</h1>

		<h3><?=__('Impressions')?></h3>
		<div id="big_stats" class="cf">
			<div class="stat">		
				<h4><?=$today_hits?></h4>			
				<?=__('Today')?>
			</div> <!-- .stat -->
			
			<div class="stat">				
				<h4><?=$yes_hits?></h4>			
				<?=__('Yesterday')?>					
			</div> <!-- .stat -->
			
			<div class="stat">			
				<h4><?=$month_hits?></h4>			
				<?=__('Month')?>						
			</div> <!-- .stat -->
			
			<div class="stat">					
				<h4><?=$total_hits?></h4>			
				<?=__('Total')?>					
			</div> <!-- .stat -->
			
		</div> <!-- /#big_stats -->

		<h3><?=__('Clicks')?></h3>
		<div id="big_stats" class="cf">
			<div class="stat">		
				<h4><?=$today_clicks?></h4>			
				<?=__('Today')?>
			</div> <!-- .stat -->
			
			<div class="stat">				
				<h4><?=$yes_clicks?></h4>			
				<?=__('Yesterday')?>					
			</div> <!-- .stat -->
			
			<div class="stat">			
				<h4><?=$month_clicks?></h4>			
				<?=__('Month')?>						
			</div> <!-- .stat -->
			
			<div class="stat">					
				<h4><?=$total_clicks?></h4>			
				<?=__('Total')?>					
			</div> <!-- .stat -->
			
		</div> <!-- /#big_stats -->

		<h3><?=__('Filter by date')?></h3>
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

		
		
	</div> <!-- /.span12 -->


	
</div> <!-- /.row -->


<div class="row">
			
	<div class="span6">
		
		<h3><?=__('Impressions')?></h3>
		
		<hr />
		
		<?=Chart::column($daily_hits,array('title'=>__('Daily hits ads'),'height'=>400,'width'=>600))?>

		
	</div> <!-- /.span6 -->
	
	<div class="span6">
		
		<h3><?=__('Clicks')?></h3>
		
		<hr />
		
		<?=Chart::column($daily_clicks,array('title'=>__('Daily clicks ads'),'height'=>400,'width'=>600))?>
		
	</div> <!-- /.span6 -->
							
</div> <!-- /row -->