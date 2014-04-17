<?php defined('SYSPATH') or die('No direct script access.');?>

		<div class="page-header">
			<h1><?=__('Edit Profile')?></h1>
		</div>

		<form class="well form-horizontal"  method="post" action="<?=Route::url('default',array('controller'=>'profile','action'=>'edit'))?>"> 
        <div class="control-group">
					<?= FORM::label('name', __('Name'), array('class'=>'control-label', 'for'=>'name'))?>
					<div class="controls">
						<?= FORM::input('name', $user->name, array('class'=>'input-xlarge', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
					<div class="controls">
						<?= FORM::input('email', $user->email, array('class'=>'input-xlarge', 'id'=>'email', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
					</div>
				</div>
        <div class="control-group">
          <?= FORM::label('paypal_email', __('Paypal'), array('class'=>'control-label', 'for'=>'paypal_email'))?>
          <div class="controls">
            <?= FORM::input('paypal_email', $user->paypal_email, array('class'=>'input-xlarge', 'id'=>'paypal_email', 'type'=>'email' ,'placeholder'=>__('Paypal Email')))?>
          </div>
        </div>
        <div class="control-group">
          <?= FORM::label('website', __('Website'), array('class'=>'control-label', 'for'=>'website'))?>
          <div class="controls">
            <?= FORM::input('website', $user->website, array('class'=>'input-xlarge', 'id'=>'website', 'placeholder'=>__('url of you main site')))?>
          </div>
        </div>
				

				<div class="form-actions">
					<?= FORM::button('submit', __('Send'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default',array('controller'=>'profile','action'=>'edit'))))?>
				</div>
		</form>

	
    	<div class="page-header">
    		<h1><?=__('Change password')?></h1>
    	</div>
    	
    	<form class="well form-horizontal"  method="post" action="<?=Route::url('default',array('controller'=>'profile','action'=>'changepass'))?>">         
              <?=Form::errors()?>  
              
              <div class="control-group">
                <label class="control-label"><?=__('New password')?></label>
                <div class="controls docs-input-sizes">
                <input class="input-medium" type="password" name="password1" placeholder="<?=__('Password')?>">
                </div>
              </div>
              
              <div class="control-group">
                <label class="control-label"><?=__('Repeat password')?></label>
                <div class="controls docs-input-sizes">
                <input class="input-medium" type="password" name="password2" placeholder="<?=__('Password')?>">
                  <p class="help-block">
                  		<?=__('Type your password twice to change')?>
                  </p>
                </div>
              </div>
              
              <div class="form-actions">
              	<a href="<?=Route::url('default')?>" class="btn"><?=__('Cancel')?></a>
                <button type="submit" class="btn btn-primary"><?=__('Send')?></button>
              </div>
    	</form>
