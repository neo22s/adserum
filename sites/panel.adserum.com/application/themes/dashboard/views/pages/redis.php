<div class="row">
		
		<div class="span12">
			<h2><?=__('Redis')?> <?=$redis['Server']['redis_version']?></h2>

			<div id="big_stats" class="cf">
			
				<div class="stat">		
					<h4><?=date::secs_to_time($redis['Server']['uptime_in_seconds'])?></h4>			
					<?=__('Uptime')?> -
					<?=date('Y-m-d h:i',time()-$redis['Server']['uptime_in_seconds'])?>
				</div> <!-- .stat -->

				<div class="stat">					
					<h4><?=date::secs_to_time(time()-$redis['Persistence']['rdb_last_save_time'])?></h4>			
					<?=__('Saved')?> -
					<?=date('Y-m-d h:i',$redis['Persistence']['rdb_last_save_time'])?>
				</div> <!-- .stat -->
				
				<div class="stat">			
					<h4><?=$redis['CPU']['used_cpu_sys']?>/<?=$redis['CPU']['used_cpu_user']?></h4>			
					<?=__('CPU')?>						
				</div> <!-- .stat -->
				
				<div class="stat">			
					<h4><?=$redis['Memory']['used_memory_human']?>/<?=$redis['Memory']['used_memory_peak_human']?></h4>			
					<?=__('Memory')?>						
				</div> <!-- .stat -->
			
			
			</div> <!-- /#big_stats -->


			<div id="big_stats" class="cf">
	
				<div class="stat">				
					<h4><?=$redis['Clients']['connected_clients']?></h4>			
					<?=__('Clients')?>					
				</div> <!-- .stat -->

				<div class="stat">				
					<h4><?=$redis['Stats']['total_connections_received']?></h4>			
					<?=__('Connections')?>					
				</div> <!-- .stat -->
				
				<div class="stat">				
					<h4><?=$redis['Stats']['total_commands_processed']?></h4>			
					<?=__('Commands')?>					
				</div> <!-- .stat -->

				<div class="stat">				
					<h4><?=$redis['Keyspace']['db0']['keys']?></h4>			
					<?=__('Keys')?>					
				</div> <!-- .stat -->
				
			
			</div> <!-- /#big_stats -->
		</div>

		<?foreach ($redis as $key => $value):?>
			<div class="span6">
			<h4><?=$key?></h4>
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Field</th>
						<th>Value</th>
					</tr>						
				</thead>
				<tbody>
				<?foreach ($value as $element => $val):?>
					<tr>
						<td><?=$element?></td>
						<td><?=print_r($val,1)?></td>
					</tr>	
				<?endforeach?>
				</tbody>
			</table>
			</div>
		<?endforeach?>
	
</div> <!-- /.row -->