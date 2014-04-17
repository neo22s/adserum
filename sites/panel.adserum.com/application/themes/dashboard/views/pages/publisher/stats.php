
<div class="row">


	<div class="span12">
		<h1><?=__('Publisher Stats')?></h1>
		<p><?=__('Aproximate numbers before review.')?> <?=__('You get paid $')?><?=core::config('common.publisher_CPM')?> CPM</p>
		<p><?=__('Your publisher id is')?> <code><?=$user->id_user?></code>

		<?if(Auth::instance()->get_user()->id_role==1):?>
			<a href="<?=Route::url('default',array('controller'=>'user','action'=>'update','id'=>$user->id_user));?>"><?=$user->email?></a>
		<?endif?>

		</p>

		<h3><?=__('Impressions')?></h3>
		<div id="big_stats" class="cf">
			<div class="stat">		
				<?=__('Today')?>
				<h4><?=$today_hits?></h4>		
				<h5><?=Controller_Publisher::money_cpm($today_hits)?></h5>	
			</div> <!-- .stat -->
			
			<div class="stat">	
				<?=__('Yesterday')?>			
				<h4><?=$yes_hits?></h4>			
				<h5><?=Controller_Publisher::money_cpm($yes_hits)?></h5>
			</div> <!-- .stat -->
			
			<div class="stat">	
				<?=__('Month')?>			
				<h4><?=$month_hits?></h4>
				<h5><?=Controller_Publisher::money_cpm($month_hits)?></h5>			
			</div> <!-- .stat -->
			
			<div class="stat">			
				<?=__('Total')?>			
				<h4><?=$total_hits?></h4>		
				<h5><?=Controller_Publisher::money_cpm($total_hits)?></h5>	
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

	<div class="span12">	
		
		<?=Chart::column($daily_hits,array('title'=>__('Daily impressions'),'height'=>400,'width'=>'100%'))?>

		
	</div> <!-- /.span6 -->



<?
function print_scores($scores){?>
<?foreach ($scores as $h):?>
<tr>
	<td class="description">
			<a href="http://<?=$h[0]?>"><?=$h[0]?></a>
	</td>
	<td class="value"><span><?=$h[1]?></span></td>
</tr>
<?endforeach?>
<?}?>

<div class="row">
	<div class="span12">
		<h2><?=__('Domains names')?></h2>
	</div>
	<div class="span4">
		<h3><?=__('Today')?></h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=__('Domain')?></th><th><?=_('Impressions')?></th></tr>
			</thead>
			<tbody>
				<?print_scores($domains_today)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->

	<div class="span4">
		<h3><?=__('Yesterday')?></h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=__('Domain')?></th><th><?=_('Impressions')?></th></tr>
			</thead>
			<tbody>
				<?print_scores($domains_yes)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->
			
	<div class="span4">
		<h3><?=__('Total')?></h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=__('Domain')?></th><th><?=_('Impressions')?></th></tr>
			</thead>
			<tbody>
				<?print_scores($domains_all)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->			
			

	<div class="span4">
		<h3><?=__('Month')?></h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=__('Domain')?></th><th><?=_('Impressions')?></th></tr>
			</thead>
			<tbody>
				<?print_scores($domains_month)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->

	<div class="span4">
		<h3><?=__('Last Month')?></h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=__('Domain')?></th><th><?=_('Impressions')?></th></tr>
			</thead>
			<tbody>
				<?print_scores($domains_last_month)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->
			
	<div class="span4">
		<h3><?=__('Year')?></h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=__('Domain')?></th><th><?=_('Impressions')?></th></tr>
			</thead>
			<tbody>
				<?print_scores($domains_year)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->			
			
</div> <!-- /.row -->

	
</div> <!-- /.row -->


