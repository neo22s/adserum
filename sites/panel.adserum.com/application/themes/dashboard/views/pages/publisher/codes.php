<p><?=__('You get paid $')?><?=core::config('common.publisher_CPM')?> CPM. <?=__('Your publisher id is')?> <code><?=Auth::instance()->get_user()->id_user?></code></p>

<?=Model_Content::text('affiliate.codes')?>

<?foreach ($formats as $f):?>
<div class="row">
	
	<div class="span4">
		<h3 class="title"><?=$f->name?></h3>
		<table class="table">
				<tr>
					<td><?=__('Advertisements')?></td>
					<td><?=$f->max_ad_slots?></td>
				</tr>
				<tr>
					<td><?=__('Size')?></td>
					<td><?=$f->width?>x<?=$f->height?> px</td>
				</tr>
				<tr>
					<td><?=__('Orientation')?></td>
					<td><?=__($f->orientation)?></td>
				</tr>
		</table>
		<p><?=__('JavaScript Code')?>:</p>
		Sync (any browser)
		<textarea class="span4" onclick="this.select()"><?=View::factory('pages/publisher/sync',array('user'=>$user,'f'=>$f))?></textarea>
		Async BETA (only modern browsers)
		<textarea class="span4" onclick="this.select()"><?=View::factory('pages/publisher/async',array('user'=>$user,'f'=>$f))?></textarea>
	</div> <!-- /.span4 -->
	
	<div class="span8">
		<h3 class="title"><?=__('Example')?> <?=$f->name?></h3>
		<?=View::factory('pages/publisher/sync',array('user'=>$user,'f'=>$f))?>
	</div> <!-- /.span8 -->

</div> <!-- /.row -->
<?endforeach?>