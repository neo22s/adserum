<div class="account-container login stacked">
  
  <div class="content clearfix">
    
    <form method="post" action="<?=Route::url('default',array('controller'=>'auth','action'=>'login'))?>">
      <?=Form::errors()?>
      <h1><?=__('Sign In')?></h1>    
      
      <div class="login-fields">
        
        <p><?=__('Sign in using your registered account')?>:</p>
        
        <div class="field">
          <label for="email"><?=__('Email')?>:</label>
          <input type="text" id="email" name="email" value="" placeholder="<?=__('Email')?>" class="login username-field" />
        </div> <!-- /field -->
        
        <div class="field">
          <label for="password"><?=__('Password')?>:</label>
          <input type="password" id="password" name="password" value="" placeholder="<?=__('Password')?>" class="login password-field"/>
        </div> <!-- /password -->
        
      </div> <!-- /login-fields -->
      
      <div class="login-actions">
        
        <span class="login-checkbox">
          <input id="Field" name="remember" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4" />
          <label class="choice" for="Field"><?=__('Keep me signed in')?></label>
        </span>
                  
        <button class="button btn btn-primary btn-large"><?=__('Sign In')?></button>
        
      </div> <!-- .actions -->
      
    <?=Form::CSRF('login')?>
    </form>       
    
  </div> <!-- /content -->
  
</div> <!-- /account-container -->


<!-- Text Under Box -->
<div class="login-extra">
  <?=__("Don't have an account?")?> <a href="<?=Route::url('default',array('controller'=>'auth','action'=>'register'))?>"><?=__('Sign Up')?></a><br/>
  <?=__('Remind')?> <a href="<?=Route::url('default',array('controller'=>'auth','action'=>'forgot'))?>"><?=__('Password')?></a>
</div> <!-- /login-extra -->