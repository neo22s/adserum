<div class="logo_new_advert">
    <a rel="home" title="Adserum" href="http://adserum.com/" class="brand">
        <img src="http://adserum.com/wp-content/themes/adserum/img/logo.png">
    </a>
    <div class="slogan"><?=__('Online Advertisement made simple!')?></div>
</div>

<div class="account-container register stacked shift_top">
  <div class="stacked preview-container">
  <div class="preview_header">
      <h3><?=__('Preview advertisement')?></h3>
  </div>
      <ul>
          <li class="first">
              <a class="title" href="#"><?=(core::request('title')!=NULL)?core::request('title'):__('Advertisement Title')?></a>
              <br>
              <a class="desc" href="#"><?=(core::request('desc')!=NULL)?core::request('desc'):__('Description Line')?>
              <br>
              </a><p class="desc2"><?=(core::request('desc2')!=NULL)?core::request('desc2'):__('Description Line 2')?> 2</p>
              <a class="url" href="#"><?=__('Url to Display')?></a>
              
          </li>
          <a class="powered_by_logo" target="_blank" href="http://adserum.com/">
              <img src="http://api.adserum.com/images/adserum_bottom.png">
          </a>
      </ul>
    
  </div>
    <div class="btn-group pull-right btn_lang">
            <a href="#" class="btn-warning btn-small dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-flag icon-white"></i> <?=ucfirst(Controller::$lang->language_name)?> <b class="caret"></b>
            </a>
              <ul class="dropdown-menu">
                <?foreach (Model_Language::get_all() as $l): ?>
                    <?if ($l->language!=Controller::$lang->language):?>
                    <li><a href="<?=Route::url('new-ad')?>?lang=<?=$l->language?>"><?=ucfirst($l->language_name)?></a></li>  
                    <?endif?>            
                <?endforeach?>
              </ul>
        </div>
  <div class="content clearfix mt-15">     
    
    <form method="post" action="<?=Route::url('new-ad')?>">
      <?=Form::errors()?>
      <h1><?=__('New Advertisement')?></h1>    

      <div class="login-fields">
        
        <?php if ($errors): ?>
                <p class="message"><?=__('Some errors were encountered, please check the details you entered.')?></p>
                <ul class="errors">
                <?php foreach ($errors as $message): ?>
                    <li><?php echo $message ?></li>
                <?php endforeach ?>
                </ul>
                <?php endif ?>


        <?if (!Auth::instance()->logged_in()):?>
            <h3><?=__('Create your new account')?></h3>

            <div class="control-group">
                  <label class="control-label" for="subject"><?=__('Name')?></label>
                  <div class="controls">
                    <input type="text" class="input-large" name="name" id="name" placeholder="<?=__('Your Name')?>" value="<?=core::request('name')?>"/>
                  </div>
                </div>

            <div class="control-group">
                  <label class="control-label" for="subject"><?=__('Email')?></label>
                  <div class="controls">
                    <input type="text" class="input-large" name="email" id="email" placeholder="<?=__('Your Email')?>" value="<?=core::request('email')?>">
                  </div>
                </div>

            <div class="login-extra">
              <?=__("Already have an account?")?> <a href="<?=Route::url('default',array('controller'=>'auth','action'=>'login'))?>"><?=__('Sign In')?></a>.
            </div> <!-- /login-extra -->

            <hr>
        <?endif?>
 
                <h3>1 - <?=__('Advertisement details')?></h3>
                <div class="control-group">
                  <label class="control-label" for="subject"><?=__('Title')?></label>
                  <div class="controls">
                    <input type="text" class="input-large" name="title" id="title" placeholder="<?=__('Advertisement Title')?>" value="<?=core::request('title')?>">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="desc"><?=__('Description Line')?></label>
                  <div class="controls">
                    <input type="text" class="input-large" name="desc" id="desc" placeholder="<?=__('Description Line')?>" value="<?=core::request('desc')?>">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="desc2"><?=__('Description Line')?> 2</label>
                  <div class="controls">
                    <input type="text" class="input-large" name="desc2" id="desc2" placeholder="<?=__('Description Line')?> 2" value="<?=core::request('desc2')?>">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="url"><?=__('Url')?></label>
                  <div class="controls">
                    <input type="text" class="input-large" name="url" id="url" placeholder="<?=__('Destination URL')?>" value="<?=core::request('url')?>">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="durl"><?=__('Url to Display')?></label>
                  <div class="controls">
                    <input type="text" class="input-large" name="durl" id="durl" placeholder="<?=__('Url to Display')?>" value="<?=core::request('durl')?>">
                  </div>
                </div>
              

                
                <h3>2 - <?=__('Advertisement target')?></h3>
                <div class="control-group">
                <label class="control-label label_true" for="lang"><?=__('Advertisement Language')?></label>
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
                  <label class="control-label label_true" for="countries"><?=__('Select Countries to browse cities')?></label>
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
                  <label class="control-label label_true" for="city"><?=__('Search for cities')?></label>
                  <div class="controls">
                    <input type="text" id="city" placeholder="<?=__('Type name of the City')?>" data-source="<?=Route::url('default',array('controller'=>'auth','action'=>'cities'))?>" 
                            class="input-large" value="" autocomplete="off">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label label_true" for="cities"><?=__('Selected cities')?></label>
                  <div class="controls">
                    <select name="cities[]" id="cities" MULTIPLE>
                        <option selected value="<?=$geo['id_city']?>"><?=$geo['city']?></option>
                    </select>
                   </div>
                </div>

                <h3>3 - <?=__('Choose campaign')?></h3>
                <div class="control-group">
                  <label class="control-label" for="product"><?=__('Choose campaign')?></label>
                  <div class="controls">
                    <select name="product" id="product">
                        <?foreach ($products as $p):?>
                        <option value="<?=$p->id_product?>" data-price="<?=$p->price?>"><?=$p->name?></option>
                        <?endforeach?>
                    </select>
                   </div>
                </div>
        
      <hr>
        
      </div> <!-- /login-fields -->
      
      <div class="login-actions">
        
        <button class="button btn btn-primary btn-large price_new_auth"><?=__('Publish FREE')?></button>
        <input  class="price_new_auth_hidden" value="<?=__("Pay with PayPal")?>"/>
        
        <span class="login-checkbox" >
          <input id="terms" name="terms" type="checkbox" class="field login-checkbox" CHECKED />
          <a href="http://adserum.com/terms-and-conditions/" target="_blank"><?=__('I have read and agree with the Terms of Use')?>.</a>
        </span>
        
      </div> <!-- .actions -->
      
    <?=Form::CSRF('new')?>
    </form>       
    
  </div> <!-- /content -->
  
</div> <!-- /account-container -->


