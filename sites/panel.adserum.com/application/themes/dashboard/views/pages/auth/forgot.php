<div class="account-container login stacked">
  
  <div class="content clearfix">
    
    <form method="post" action="<?=Route::url('default',array('controller'=>'auth','action'=>'forgot'))?>">
      <?=Form::errors()?>
      <h1><?=__('Remember password')?></h1>    
      
      <div class="login-fields">
        
        <p><?=__('Use your registered email, we will send you an email')?>:</p>
        
        <div class="field">
          <label for="email"><?=__('Email')?>:</label>
          <input type="text" id="email" name="email" value="" placeholder="<?=__('Email')?>" class="login username-field" />
        </div> <!-- /field -->
                
      </div> <!-- /login-fields -->
      
      <div class="login-actions">
        
        <button class="button btn btn-primary btn-large"><?=__('Remember')?></button>
        
      </div> <!-- .actions -->
            
    <?=Form::CSRF('forgot')?>
    </form>       
    
  </div> <!-- /content -->
  
</div> <!-- /account-container -->


<!-- Text Under Box -->
<div class="login-extra">
  <?=__("Don't have an account?")?> <a href="<?=Route::url('default',array('controller'=>'auth','action'=>'register'))?>"><?=__('Sign Up')?></a><br/>
  <?=__('go back')?> <a href="<?=Route::url('default',array('controller'=>'auth','action'=>'login'))?>"><?=__('Sign In')?></a>
</div> <!-- /login-extra -->