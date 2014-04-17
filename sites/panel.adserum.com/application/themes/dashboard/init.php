<?php
//twitter theme initialization
View::$styles	            = array(
									'css/application-ocean-breeze.css' => 'screen',
									'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap-combined.min.css' => 'screen',
									'http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css' => 'screen',
									'http://fonts.googleapis.com/css?family=Open+Sans:400,600,800' => 'screen',
									'css/chosen.css' => 'screen',
								);

//View::$scripts['header']	= array('js/libs/modernizr-2.5.3.min.js');


View::$scripts['footer']	= array('http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',
									'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js',
									'js/libs/chosen.jquery.min.js',
									'js/Theme.js',
									'js/common.js',
									);




/**
 * custom error alerts
 */
Form::$errors_tpl ='<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>
			        <h4 class="alert-heading">%s</h4>
			        <ul>%s</ul></div>';
Form::$error_tpl = '<div class="alert"><a class="close" data-dismiss="alert">×</a>%s</div>';


Alert::$tpl = '<div class="alert alert-%s">
					<a class="close" data-dismiss="alert" href="#">×</a>
					<h4 class="alert-heading">%s</h4>%s
				</div>';


/**
 * Theme Functions
 * 
 */

/**
 * @todo this belongs to the admin, so needs to be loaded no matter, the theme. not a good place here...
 * generates a link used in the admin sidebar
 * @param  string $name       translated name in the A
 * @param  string $controller
 * @param  string $action     
 * @param  string $route      
 */
function theme_link($name,$controller,$action='index',$route='default', $icon = NULL)
{	
	if (Auth::instance()->get_user()->has_access($controller))
 	{
 	?>
		<li <?=(Request::current()->controller()==$controller 
				&& Request::current()->action()==$action)?'class="active"':''?> >
			<a href="<?=Route::url($route,array('controller'=>$controller,
												'action'=>$action))?>">
				<?if ($icon!==NULL):?>
					<i class="icon-<?=$icon?>"></i>
				<?endif?>
				<?=$name?>
			</a>
		</li>
	<?
	}
}

function theme_link_id($name,$controller,$action='index',$id,$route='default', $icon = NULL)
{	
	if (Auth::instance()->get_user()->has_access($controller))
 	{
 	?>
		<li <?=(Request::current()->controller()==$controller 
				&& Request::current()->action()==$action
				&& Request::current()->param('id')==$id)?'class="active"':''?> >
			<a href="<?=Route::url($route,array('controller'=>$controller,
												'action'=>$action,'id'=>$id))?>">
				<?if ($icon!==NULL):?>
					<i class="icon-<?=$icon?>"></i>
				<?endif?>
				<?=$name?>
			</a>
		</li>
	<?
	}
}