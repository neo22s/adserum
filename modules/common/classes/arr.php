<?php 

/**
 * Array helper class.
 *
 *
 * @package    OC
 * @category   Helpers
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */

class Arr extends Kohana_Arr {


	/**
	 * array_rand replace, that works!
	 * @param  array  $arr
	 * @param  integer $num 
	 * @return array       
	 */
	public static function random($arr, $num = 1) 
	{
		$num_arr = count($arr);

		//there's nothing to shuffle
		if ($num_arr <= 1)
			return $arr;


	    shuffle($arr);
	    
	    $r = array();
	     //just in case we try to shuffle and we don't have enough values
	    if ($num > $num_arr) $num = $num_arr;
	    
	    //only 1 to return
	    if ($num == 1)
	    {
	    	$r[] = $arr[0];
	    }
	    //more than 1
	    else
	    {
	    	for ($i = 0; $i < $num; $i++) 
	        	$r[] = $arr[$i];
	    }
	    
	    return $r;
	}

	/**
	 * array random for assoc
	 * @param  array  $arr 
	 * @param  integer $num 
	 * @return array       
	 */
	public static function random_assoc($arr, $num = 1) 
	{
	    $keys = array_keys($arr);
	    shuffle($keys);

	    //just in case we try to shuffle and we don't have enough values
	    $num_arr = count($keys);
	    if ($num > $num_arr) $num = $num_arr;

	    $r = array();
	    for ($i = 0; $i < $num; $i++) 
	    {
	        $r[$keys[$i]] = $arr[$keys[$i]];
	    }

	    return $r;
	}
}