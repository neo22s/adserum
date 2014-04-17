	
<div id="topbar">
	
	<div class="container">
		
		<a href="javascript:;" id="menu-trigger" class="dropdown-toggle" data-toggle="dropdown" data-target="#">
			<i class="icon-cog"></i>
		</a>
	
		<div id="top-nav">
			<ul>
				<li class="dropdown">
					 <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		              	<i class="icon-flag icon-white"></i> <?=ucfirst(Controller::$lang->language_name)?> <b class="caret"></b></a>
					
					<ul class="dropdown-menu pull-right">
						<?foreach (Model_Language::get_all() as $l): ?>
                            <?if ($l->language!=Controller::$lang->language):?>
		                	<li><a href="<?=Route::url('default')?>?lang=<?=$l->language?>"><?=ucfirst($l->language_name)?></a></li>  
                            <?endif?>            
		                <?endforeach?>
					</ul> 
				</li>
				
			</ul>
			
			<ul class="pull-right">

				<?
				$arr_controllers = array('publisher');
				if (Auth::instance()->get_user()->has_access_to_any($arr_controllers)):?>
				<li class="dropdown <?=(in_array(Request::current()->controller(),$arr_controllers)?'active':'')?>">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-money"></i>
						<span><?=__('Publisher')?></span> 
						<b class="caret"></b>
					</a>	
					<ul class="dropdown-menu">
					<?theme_link(__('HTML Codes'),'publisher','codes','default','list-alt')?>
					<?theme_link(__('Stats'),'publisher','stats','default','tasks')?>
					<?theme_link(__('Payments'),'publisher','payments','default','money')?>
					</ul> 
				</li>
				<? endif?>


				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-user"></i><?=Auth::instance()->get_user()->email?>
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu pull-right">
						<?theme_link(__('Edit profile'),'profile','edit','default','edit')?>
						<?theme_link(__('Change Password'),'profile','edit','default','lock')?>
						<li class="divider"></li>
						<?theme_link(__('Logout'),'auth','logout','default','off')?>
					</ul> 
				</li>
				
			</ul>
			
		</div> <!-- /#top-nav -->
		
	</div> <!-- /.container -->
	
</div> <!-- /#topbar -->

	
<div id="header">
	
	<div class="container">
		
		<a href="<?=Route::url('default')?>" class="brand">Adserum.com</a>
		
		<a href="javascript:;" class="btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        	<i class="icon-reorder"></i>
      	</a>
	
		<div class="nav-collapse">
			<ul id="main-nav" class="nav pull-right">
				<li class="nav-icon <?=(Request::current()->controller()=='home' 
					&& Request::current()->action()=='index')?'active':''?>">
					<a href="<?=Route::url('default')?>">
						<i class="icon-home"></i>
						<span><?=__('Home')?></span>        					
					</a>
				</li>
				
				<?
				if (Auth::instance()->get_user()->id_role==1)://@todo not hardcoded?>
				

				<?theme_link(__('Ads Moderation'),'moderation','index','default','tasks')?>
				<?
				$arr_controllers = array('stats');
				if (Auth::instance()->get_user()->has_access_to_any($arr_controllers)):?>
				<li class="dropdown <?=(in_array(Request::current()->controller(),$arr_controllers)?'active':'')?>">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-signal"></i>
						<span><?=__('Stats')?></span> 
						<b class="caret"></b>
					</a>	
					<ul class="dropdown-menu">
						<?theme_link(__('Dashboard'),'stats','panel')?>
						<?theme_link_id('Domain','stats','score','domain')?>
						<?theme_link_id('Browser','stats','score','browser')?>
						<?theme_link_id('Platforms','stats','score','platform')?>
						<?theme_link_id('Publishers','stats','score','id_affiliate')?>
						<?theme_link_id('Formats','stats','score','id_adformat')?>
						<?theme_link_id('Languages','stats','score','id_language')?>
						<?theme_link_id('Locations','stats','score','id_location')?>
					</ul> 
				</li>
				<? endif?>

				<?
				$arr_controllers = array('location','language','content','product','adformat','order');
				if (Auth::instance()->get_user()->has_access_to_any($arr_controllers)):?>
				<li class="dropdown <?=(in_array(Request::current()->controller(),$arr_controllers)?'active':'')?>">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-pencil"></i>
						<span><?=__('Content')?></span> 
						<b class="caret"></b>
					</a>	
					<ul class="dropdown-menu">
						<?theme_link(__('Locations'),'location')?>
						<?theme_link(__('Languages'),'language')?>
                        <?theme_link(__('Translations'),'translations')?>
						<?theme_link(__('Content'),'content')?>
						<?theme_link(__('Ads Formats'),'adformat')?>
						<?theme_link(__('Products'),'product')?>
						<?theme_link(__('Orders'),'order')?>
					</ul> 
				</li>
				<? endif ?>

				<?
				$arr_controllers = array('user','role','access');
				if (Auth::instance()->get_user()->has_access_to_any($arr_controllers)):?>
				<li class="dropdown <?=(in_array(Request::current()->controller(),$arr_controllers)?'active':'')?>">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-user"></i>
						<span><?=__('Users')?></span>
						<b class="caret"></b> 
					</a>	
					<ul class="dropdown-menu">
						<?theme_link(__('Users'),'user')?>
						<?theme_link(__('User Roles'),'role')?>
						<?theme_link(__('Roles Access'),'access')?>
					</ul> 
				</li>
				<? endif ?>

				<?
				$arr_controllers = array('crontab','cronjob','tools','log','redis');
				if (Auth::instance()->get_user()->has_access_to_any($arr_controllers)):?>
				<li class="dropdown <?=(in_array(Request::current()->controller(),$arr_controllers)?'active':'')?>">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-wrench"></i>
						<span><?=__('Tools')?></span> 
						<b class="caret"></b>
					</a>	
					<ul class="dropdown-menu">
					<?theme_link(__('Crontab'),'crontab')?>
					<?theme_link(__('Cronjobs'),'cronjob')?>
					<?theme_link(__('Clean cache'),'tools','cache')?>
					<?theme_link(__('Logs'),'log')?>
					<?theme_link(__('Redis'),'redis')?>
					</ul> 
				</li>
				<? endif?>

				<? endif ?>
				
				<?
				//NORMAL USERS
				$arr_controllers = array('ads');
				if (Auth::instance()->get_user()->has_access_to_any($arr_controllers)):?>
					<?theme_link(__('Advertisements'),'ads','index','default','list')?>
					<?theme_link(__('New Advertisement'),'ads','new','default','leaf')?>
				<? endif?>

			</ul>
			
		</div> <!-- /.nav-collapse -->

	</div> <!-- /.container -->
	
</div> <!-- /#header -->	