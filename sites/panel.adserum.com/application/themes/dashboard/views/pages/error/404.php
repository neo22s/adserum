<div class="row">
			
	<div class="span12">
		
		<div class="error-container">
			
			<h1><?=__('Oops!')?></h1>
			
			<h2>404 <?=__('Page Not Found')?></h2>
			
			<div class="error-details">
				<?=Model_Content::text('404',array('[REQ.PAGE]'=>HTML::anchor(Arr::get($_SERVER, 'REQUEST_URI'), Arr::get($_SERVER, 'REQUEST_URI'))))?>
			</div> <!-- /error-details -->
			
			<div class="error-actions">
				<a href="<?=Route::url('default')?>" class="btn btn-large btn-primary">
					<i class="icon-chevron-left"></i>
					&nbsp;
					<?=__('Back to Dashboard')?>						
				</a>				
			</div> <!-- /error-actions -->
						
		</div> <!-- /.error-container -->				
		
		
	</div> <!-- /.span12 -->

</div> <!-- /.row -->		