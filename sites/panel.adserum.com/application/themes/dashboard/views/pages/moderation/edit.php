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
			        <input type="text" class="input-large" name="title" id="title" value="<?=$ad->title?>">
			      </div>
			    </div>

			    <div class="control-group">
			      <label class="control-label" for="desc"><?=__('Description Line')?></label>
			      <div class="controls">
			        <input type="text" class="input-large" name="desc" id="desc" value="<?=$ad->description?>">
			      </div>
			    </div>

			    <div class="control-group">
			      <label class="control-label" for="desc2"><?=__('Description Line')?> 2</label>
			      <div class="controls">
			        <input type="text" class="input-large" name="desc2" id="desc2" value="<?=$ad->description2?>">
			      </div>
			    </div>

			    <div class="control-group">
			      <label class="control-label" for="url"><?=__('Url')?></label>
			      <div class="controls">
			        <input type="text" class="input-large" name="url" id="url" value="<?=$ad->click_url?>">
			      </div>
			    </div>

			    <div class="control-group">
			      <label class="control-label" for="durl"><?=__('Url to Display')?></label>
			      <div class="controls">
			        <input type="text" class="input-large" name="durl" id="durl" value="<?=$ad->display_url?>">
			      </div>
			    </div>

			   <div class="control-group">
			      <label class="control-label" for="displays"><?=__('Displays')?></label>
			      <div class="controls">
			        <input type="text" class="input-large" name="displays" id="displays" value="<?=$ad->displays?>">
			      </div>
			    </div>

			   <div class="control-group">
			      <label class="control-label" for="created"><?=__('Created')?></label>
			      <div class="controls">
			        <input type="text" class="input-large" name="created" id="created" DISABLED value="<?=$ad->created?>">
			      </div>
			    </div>

			   <div class="control-group">
			      <label class="control-label" for="status"><?=__('Status')?></label>
			      <div class="controls">
                    <select id="status" name="status">
                        <?foreach (Model_Ad::$statuses as $status=>$text):?>
                            <option value="<?=$status?>" <?=($ad->status==$status)?'selected':''?> ><?=$text?></option>
                        <?endforeach?>
                    </select>
			      </div>
			    </div>


			   </div> 	    
			    

			  <div class="span6">
			    <h3>2 - <?=__('Advertisement target')?></h3>

			    <div class="control-group">
			      <label class="control-label" for="ip"><?=__('IP address INFO')?></label>
			      <div class="controls">
			        <input type="text" class="input-large" name="ip_address" id="ip_address" DISABLED value="<?=$ad->ip_address?>"> 
			      </div>

			      <div class="controls">
			        <?=long2ip($ad->ip_address)?> - <?print_r($geoip)?>
			      </div>
			    </div>


			    <div class="control-group">
	            <label class="control-label" for="lang"><?=__('Advertisement Language')?></label>
	            <div class="controls">
	              <select id="lang" name="lang">
	              		<option <?=(Controller::$lang->id_language==$ad->id_language)?'selected':''?>
	              		  value="<?=Controller::$lang->id_language?>"><?=ucfirst(Controller::$lang->language_name)?></option>
	              <?foreach (Model_Language::get_all() as $l): ?>
		                <option <?=($l->id_language==$ad->id_language)?'selected':''?>
		                	value="<?=$l->id_language?>"><?=ucfirst($l->language_name)?></option>          
		          <?endforeach?>
	              </select>
	            </div>
	          </div>
					    

			    <div class="control-group">
			      <label class="control-label" for="locations"><?=__('Locations')?></label>
			      <div class="controls">
			      	<select name="locations[]" id="locations" MULTIPLE>
			      		<?foreach ($locations as $location):?>
			      		<option selected value="<?=$location->id_location?>" ><?=(!empty($location->city))?$location->city:$location->country?></option>
			      		<?endforeach?>
			      	</select>
				   </div>
			    </div>

			    <hr>
			    <h3>3 - <?=__('Order info')?></h3>
                <a href="<?=Route::url('default',array('controller'=>'profile','action'=>'edit','id'=>$user->id_user))?>">
                    <?=$user->name?> - <?=$user->email?></a><br>
                <?if ($order->loaded()):?>
			     <a href="<?=Route::url('default',array('controller'=>'order','action'=>'update','id'=>$order->id_order))?>">Order <?=($order->id_order)?></a><br>
			     $<?=$order->amount?> - <?=$order->date_paid?><br>
                <?endif?>
			    </div>
			</div>

			    <div class="form-actions">
			      <a class="btn btn-danger btn-small" onclick="return confirm('<?=__('Sure?')?>');"
						href="<?=Route::url('default',array('controller'=>'moderation','action'=>'delete','id'=>$ad->pk()))?>" >
						<i class="icon-remove"></i>
					</a>
			      <a class="btn btn-warning btn-small" 
			      		href="<?=Route::url('default',array('controller'=>'moderation','action'=>'index'))?>" >
			      		<i class="icon-minus-sign"></i> <?=__('Back')?>
			      </a>
			      <button type="submit" class="btn btn-primary"><i class="icon-star"></i> <?=__('Publish')?></button>

			    </div>
			  </fieldset>
			</form>
					
	</div> <!-- /.span12 -->
	
</div> <!-- /.row -->