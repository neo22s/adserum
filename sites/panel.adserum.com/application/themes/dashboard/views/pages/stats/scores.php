<h1><?=$type?> scores</h1>

<?
function print_scores($links, $scores){?>
<?foreach ($scores as $h):?>
<tr>
	<td class="description">
		<?if ($links!==NULL AND array_key_exists($h[0], $links)):?>
			<a href="<?=$links[$h[0]]['link']?>"><?=$links[$h[0]]['name']?></a>
		<?else:?>
			<?=$h[0]?>
		<?endif?>

	</td>
	<td class="value"><span><?=$h[1]?></span></td>
</tr>
<?endforeach?>
<?}?>

<div class="row">
	<div class="span12">
		<h2>Hits</h2>
	</div>
	<div class="span4">
		<h3>Today</h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=$type?></th><th>Hits</th></tr>
			</thead>
			<tbody>
				<?print_scores($links, $today_hits)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->

	<div class="span4">
		<h3>Yesterday</h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=$type?></th><th>Hits</th></tr>
			</thead>
			<tbody>
				<?print_scores($links, $yes_hits)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->
			
	<div class="span4">
		<h3>Total</h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=$type?></th><th>Hits</th></tr>
			</thead>
			<tbody>
				<?print_scores($links, $total_hits)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->			
			

	<div class="span4">
		<h3>Month</h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=$type?></th><th>Hits</th></tr>
			</thead>
			<tbody>
				<?print_scores($links, $month_hits)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->

	<div class="span4">
		<h3>Last Month</h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=$type?></th><th>Hits</th></tr>
			</thead>
			<tbody>
				<?print_scores($links, $lmonth_hits)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->
			
	<div class="span4">
		<h3>Year</h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=$type?></th><th>Hits</th></tr>
			</thead>
			<tbody>
				<?print_scores($links, $year_hits)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->			
			
</div> <!-- /.row -->


<div class="row">
	<div class="span12">
		<h2>Clicks</h2>
	</div>
	<div class="span4">
		<h3>Today</h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=$type?></th><th>clicks</th></tr>
			</thead>
			<tbody>
				<?print_scores($links, $today_clicks)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->

	<div class="span4">
		<h3>Yesterday</h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=$type?></th><th>clicks</th></tr>
			</thead>
			<tbody>
				<?print_scores($links, $yes_clicks)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->
			
	<div class="span4">
		<h3>Total</h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=$type?></th><th>clicks</th></tr>
			</thead>
			<tbody>
				<?print_scores($links, $total_clicks)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->			
			

	<div class="span4">
		<h3>Month</h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=$type?></th><th>clicks</th></tr>
			</thead>
			<tbody>
				<?print_scores($links, $month_clicks)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->

	<div class="span4">
		<h3>Last Month</h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=$type?></th><th>clicks</th></tr>
			</thead>
			<tbody>
				<?print_scores($links, $lmonth_clicks)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->
			
	<div class="span4">
		<h3>Year</h3>
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th><?=$type?></th><th>clicks</th></tr>
			</thead>
			<tbody>
				<?print_scores($links, $year_clicks)?>
			</tbody>
		</table>
	</div> <!-- /.span4 -->			
			
</div> <!-- /.row -->