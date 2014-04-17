<?php 
class Controller_Location extends Auth_Crud {



	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('country','city');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'location';




		//used to clean locations
	public function location()
	{

		$duplicates = array();

		$sql = "SELECT country,  `city` , COUNT( city ) cont
FROM  `as_locations` 
GROUP BY country,  `city` 
			HAVING cont >1 LIMIT 10000";

        $locs = Database::instance()->query(Database::SELECT, $sql,TRUE)->as_array();

//d($locs);	

        foreach($locs as $l)
        {
        	$id = new Model_Location();
        	$id->where('country','=',$l->country)
        	->where('city','=',$l->city)
        	->limit(1)->find();
           	$duplicates[] = $id->id_location;	
	        
        }

 //d($duplicates	);
      //  d(count($duplicates));


		//delete them all
		$sql = 'DELETE FROM as_locations WHERE id_location in('.implode(',', $duplicates).')';
		//d($sql);
		Database::instance()->query(Database::DELETE, $sql);

		d($sql);


	}
}
