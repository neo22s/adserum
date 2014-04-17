<?php 

// -- Routes Configuration and initialization -----------------------------------------

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */


/**
 * Error router
 */
Route::set('error', 'oc-error/<action>/<origuri>(/<message>)',
array('action' => '[0-9]++',
                          'origuri' => '.+', 
                          'message' => '.+'))
->defaults(array(
    'controller' => 'error',
    'action'     => 'index'
));


/**
 * new advertisement
 */
Route::set('new-ad', 'new-advertisement.html')
->defaults(array(
        'controller' => 'auth',
        'action'     => 'new',
));


/**
 * Default route
 */
Route::set('default', '(<controller>(/<action>(/<id>)))')
->defaults(array(
		'controller' => 'home',
		'action'     => 'index',
));
