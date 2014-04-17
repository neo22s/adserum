<div class="account-container register stacked">
  
  <div class="content clearfix">
    
    <form method="post" action="<?=Route::url('default',array('controller'=>'auth','action'=>'register'))?>">
      <?=Form::errors()?>
      <h1><?=__('Create Your Account')?></h1>    

      <div class="login-fields">
        
        <p><?=__('Create your free account')?>:</p>
        
        <div class="field">
          <label for="name"><?=__('Name')?>:</label>
          <input type="text" id="name" name="name" value="<?=Request::current()->post('name')?>" placeholder="<?=__('Name')?>" class="login" />
        </div> <!-- /field -->

        <div class="field">
          <label for="email"><?=__('Email')?>:</label>
          <input type="text" id="email" name="email" value="<?=Request::current()->post('email')?>" placeholder="<?=__('Email')?>" class="login" />
        </div> <!-- /field -->
        
        <div class="field">
          <label for="password"><?=__('Password')?>:</label>
          <input type="password" id="password" name="password1" value="" placeholder="<?=__('Password')?>" class="login"/>
        </div> <!-- /field -->
        
        <div class="field">
          <label for="confirm_password"><?=__('Confirm Password')?>:</label>
          <input type="password" id="confirm_password" name="password2" value="" placeholder="<?=__('Confirm Password')?>" class="login"/>
        </div> <!-- /field -->
      
        
      </div> <!-- /login-fields -->
      
      <div class="login-actions">
        
        <span class="login-checkbox">
          <input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4" />
          <label class="choice" for="Field"><a href="http://adserum.com/terms-and-conditions/"><?=__('I have read and agree with the Terms of Use')?>.</a></label>
        </span>
                  
        <button class="button btn btn-primary btn-large"><?=__('Register')?></button>
        
      </div> <!-- .actions -->
      
    <?=Form::CSRF('register')?>
    </form>       
    
  </div> <!-- /content -->
  
</div> <!-- /account-container -->


<!-- Text Under Box -->
<div class="login-extra">
  <?=__("Already have an account?")?> <a href="<?=Route::url('default',array('controller'=>'auth','action'=>'login'))?>"><?=__('Sign In')?></a><br/>
  <?=__('Remind')?> <a href="<?=Route::url('default',array('controller'=>'auth','action'=>'forgot'))?>"><?=__('Password')?></a>
</div> <!-- /login-extra -->