<div id="masthead">
	<div class="container">
<? if (count($breadcrumbs) > 1) : ?>
	<ul class="breadcrumb">
	<? foreach ($breadcrumbs as $crumb) : ?>
		<? if ($crumb->get_url() !== NULL) :  ?>
			<li>
				<a href="<?=$crumb->get_url()?>"><?=$crumb->get_title()?></a> 
				<span class="divider">&raquo;</span>
			</li>
		<? else : ?>
			<li class="active"><?=$crumb->get_title()?></li>
		<? endif; ?>
	<?endforeach; ?>
</ul>
<?else:?>
<div class="masthead-text">
	<h2>Welcome <?=Auth::instance()->get_user()->name?></h2>
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. @todo tips and hints</p>
</div> <!-- /.masthead-text -->
<? endif; ?>
</div> <!-- /.container -->	
	
</div> <!-- /#masthead -->
