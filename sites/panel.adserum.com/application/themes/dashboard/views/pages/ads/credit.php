
<div class="row">
	
	<div class="span12">
		<h1><?=__('Renew')?> <?=$ad->title?></h1>
		<p><?=__('From this form you can add new credit to your advertisement.')?></p>
		<h3><?=$ad->displays_left?> <?=__('Impressions left')?></h3>
		<form action="" id="contact-form" class="form-horizontal" novalidate="novalidate" method="post">
			    <h3><?=__('Campaign')?></h3>
			    <div class="control-group">
			      <label class="control-label" for="product"><?=__('Choose campaign')?></label>
			      <div class="controls">
			      	<select name="product" id="product">
			      		<?foreach ($products as $p):?>
			      		<?if ($p->price>0):?>
			      		<option value="<?=$p->id_product?>"><?=$p->name?></option>
			      		<?endif?>
			      		<?endforeach?>
			      	</select>
				   </div>
			    </div>
      	          

			    <div class="form-actions">
			      <button type="submit" class="btn btn-primary btn-large"><i class="icon-star"></i> <?=__('Pay')?></button>
			    </div>
			  </fieldset>
			</form>
					
	</div> <!-- /.span12 -->
	
</div> <!-- /.row -->