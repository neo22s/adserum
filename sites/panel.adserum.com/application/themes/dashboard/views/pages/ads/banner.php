<div class="row">
	
	<div class="span12">
		
		<form action="" id="contact-form" class="form-horizontal" novalidate="novalidate" method="post">
			<fieldset>
			    <?php if ($errors): ?>
				<p class="message"><?=__('Some errors were encountered, please check the details you entered.')?></p>
				<ul class="errors">
				<?php foreach ($errors as $message): ?>
				    <li><?php echo $message ?></li>
				<?php endforeach ?>
				</ul>
				<?php endif ?>

				<div class="row">

				<div class="span6">
			    <h3>1 - <?=__('Advertisement details')?></h3>
			    <div class="control-group">
			      <label class="control-label" for="subject"><?=__('Title')?></label>
			      <div class="controls">
			        <input type="text" class="input-large" name="title" id="title">
			      </div>
			    </div>

			    <div class="control-group">
			      <label class="control-label" for="image"><?=__('Image')?></label>
			      <div class="controls">
			        <input type="file" class="input-large" name="image" id="image">
			      </div>
			    </div>

			    <div class="control-group">
			      <label class="control-label" for="url"><?=__('Url')?></label>
			      <div class="controls">
			        <input type="text" class="input-large" name="url" id="url">
			      </div>
			    </div>

			    <div class="control-group">
			      <label class="control-label" for="durl"><?=__('Url to Display')?></label>
			      <div class="controls">
			        <input type="text" class="input-large" name="durl" id="durl">
			      </div>
			    </div>
			   </div> 

			    <div class="span6">
			    <h3>2 - <?=__('Advertisement target')?></h3>
			    <div class="control-group">
	            <label class="control-label" for="lang"><?=__('Advertisement Language')?></label>
	            <div class="controls">
	              <select id="lang" name="lang">
	              		<option selected value="<?=Controller::$lang->language?>"><?=ucfirst(Controller::$lang->language_name)?></option>
	              <?foreach (Model_Language::get_all() as $l): ?>
		                <option value="<?=$l->language?>"><?=ucfirst($l->language_name)?></option>          
		          <?endforeach?>
	              </select>
	            </div>
	          </div>
					    

			    <div class="control-group">
			      <label class="control-label" for="countries"><?=__('Select Countries to browse cities')?></label>
			      <div class="controls">
			      	<select name="countries[]" id="countries" MULTIPLE>
			      		<?foreach ($countries as $k=>$country):?>
			      		<option <?=($country['code']==$geo['country'])?'selected':''?> 
			      				value="<?=$country['code']?>"><?=$country['name']?></option>
			      		<?endforeach?>
			      	</select>
				   </div>
			    </div>

			    <div class="control-group">
			      <label class="control-label" for="city"><?=__('Search for cities')?></label>
			      <div class="controls">
			        <input type="text" id="city" data-source="<?=Route::url('default',array('controller'=>'location','action'=>'cities'))?>" 
			        		class="input-large" value="" autocomplete="off">
			      </div>
			    </div>

			    <div class="control-group">
			      <label class="control-label" for="cities"><?=__('Selected cities')?></label>
			      <div class="controls">
			      	<select name="cities[]" id="cities" MULTIPLE>
			      		<option selected value="<?=$geo['id_city']?>"><?=$geo['city']?></option>
			      	</select>
				   </div>
			    </div>

			    </div>
			</div>

			    <hr>
			    <h3>3 - <?=__('Campaign')?></h3>
			    <div class="control-group">
			      <label class="control-label" for="product"><?=__('Choose campaign')?></label>
			      <div class="controls">
			      	<select name="product" id="product">
			      		<?foreach ($products as $p):?>
			      		<option value="<?=$p->id_product?>"><?=$p->name?></option>
			      		<?endforeach?>
			      	</select>
				   </div>
			    </div>
      	          

			    <div class="form-actions">
			      <button type="submit" class="btn btn-primary btn-large"><i class="icon-star"></i> <?=__('Publish')?></button>
			    </div>
			  </fieldset>
			</form>
					
	</div> <!-- /.span12 -->
	
</div> <!-- /.row -->