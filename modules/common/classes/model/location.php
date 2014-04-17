<?php
/**
 * location 
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Auth
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 */
class Model_Location extends ORM {

  /**
   * @var  string  Table name
   */
  protected $_table_name = 'locations';

  /**
   * @var  string  PrimaryKey field name
   */
  protected $_primary_key = 'id_location';

  /**
   * meant to do only 1 query
   * @var Geoip3
   */
  protected static $_geo_record;
  protected static $_ip;

  public function __construct($id = NULL)
  {
    parent::__construct($id);
    //self::$_ip = '70.35.42.243';//SFO
    self::$_ip = Request::$client_ip;
    self::$_geo_record = NULL;
  }


	/**
	 * gets the geoip record and stores it in the static var
	 * @param  string $ip 
	 * @return geoip     
	 */
	public static function get_geoip($ip = NULL)
	{
		if ($ip===NULL)
			$ip = self::$_ip;

		if (self::$_geo_record===NULL)
		{
			//@todo store in a cookie! 1 day
			
			//not any cookie then hosted geoip :'(
			self::$_geo_record = Geoip3::instance()->record( $ip);
		}

		return self::$_geo_record;
	}


	/**
	 * get the location model for a country
	 * @param  string $country 
	 * @return Model_Location          
	 */
    public function get_country($country = NULL)
    {
    	if (empty($country) OR $country === NULL) 
    	{
    		$georecord = self::get_geoip();
    		if ($georecord!==NULL) 
    		{
    			$country = $georecord->country_code;
    		}
    		else return NULL;//not found	
    	}

		
		$location = new self();
		$location->where('country','=', $country)
				->where('city','IS',NULL)
				->limit(1)->cached()->find();

		return ($location->loaded())?$location:NULL;

    }

    /**
     * get the city model location
     * @param  string $city    
     * @param  string $country 
     * @return Model_Location        
     */
    public function get_city($city = NULL, $country = NULL)
    {	
    	if (empty($city) OR $city === NULL) 
    	{
    		$georecord = self::get_geoip();
    		if ($georecord!==NULL) 
    		{
    			$city = $georecord->city;
    		}
    		else return NULL;//not found	
    	}

		
  		$location = new self();
  		$location->where('city','=', $city);
  		if ($country!==NULL) 
  			$location->where('country','=',$country);
  		$location->limit(1)->cached()->find();

  		return ($location->loaded())?$location:NULL;

    }


    /**
     * get the location used to retrieve the ads
     * @param  string $city    
     * @param  string $country 
     * @return array       
     */
    public static function get_location($city=NULL,$country=NULL)
    {
        //location info
        $geo = Cookie::get('location');
        if ($geo===NULL)
        {
            //default values to locate the user
            $id_city    = NULL;
            $id_country = NULL;

            $location = new Model_location();

            //country
            $country  = $location->get_country(Core::get('country'));
            if ($country!==NULL)
            {
                $id_country = $country->id_location;
                $country    = $country->country;    
            }
            else $country = NULL;
            
            //get the city
            $city = $location->get_city(Core::get('city'),$country);            
            if ($city!==NULL)
            {
                $id_city = $city->id_location;
                $city    = $city->city;
            }
            else $city = NULL;
            
            //save location city and country id on cookie, 7 day?
            $geo  = array(  'city'      => $city,
                            'id_city'   => $id_city,
                            'country'   => $country,
                            'id_country'=> $id_country);
            Cookie::set('location', serialize($geo), 7*24*60*60);
        }
        //we trust the cookie...
        else $geo = unserialize($geo);


        return $geo;   

    }

    /**
     * returns an array of countries to use in geoip
     * @return array code - name
     */
    public static function get_countries()
    {
        $g  = new Geoip3();
        $ge = new GeoIP();

        $countries_codes = $ge->GEOIP_COUNTRY_CODES;
        $countries_names = $ge->GEOIP_COUNTRY_NAMES;

        $countries = array();

        foreach ($countries_codes as $id=>$cc) 
        {
            if ($cc!='' AND $cc!='AP' AND $cc!='EU' )
                $countries[$id] = array('code'=>$cc,
                                        'name'=>$countries_names[$id]);
        }
        return $countries;
    }

    /**
     * from country code name to id_location
     * @param  array  $countries ex: US,ES, NL...
     * @return array            numeric
     */
    public static function get_countries_id(array $countries)
    {
        $countries_id = array();
        
        $locs = new Model_Location();
        $locs->where('country','in',$countries)
             ->where('city','IS',NULL);
        $locs = $locs->cached()->find_all();

        foreach ($locs as $l) 
            $countries_id[] = $l->id_location;

        return $countries_id;
    }

    protected $_table_columns = array (
  'id_location' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_location',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => 'PRI',
    'privileges' => 'select,insert,update,references',
  ),
  'country' => 
  array (
    'type' => 'string',
    'exact' => true,
    'column_name' => 'country',
    'column_default' => NULL,
    'data_type' => 'char',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'character_maximum_length' => '2',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'city' => 
  array (
    'type' => 'string',
    'column_name' => 'city',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 3,
    'character_maximum_length' => '50',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  
);
}