<?php
/**
 * Ads 
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 *
 * Sets created:
 *
 *  Specific to the Ad:
 *  ads:IDAD:locations ->id_locations -- array all the locations this ad has
 *  ads:IDAD:hits ->id_hit  --- all the hits the ad had, later we can sinter with adhits:created:2013-12-25
 *  ads:IDAD:clicks ->id_hit  --- all the click the ad had, we store the id_hit to avoid repeated ones. Later we can get the clicks an ad had like 'adclick:id_ad:IAD = idsclicks'
 * 
 *  Global Lists:
 *  ads:_all_ -- all the ads ids no matter what
 *  ads:created:2013-12-21 ->id_ad --- store id_ad by date
 *  ads:created:2013-12 ->id_ad--- store id_ad by date
 *  ads:created:2013 ->id_ad--- store id_ad by date
 *  ads:user:IDUSER ->id_ad --all the ads has a user
 *  ads:moderate ->id_ad --temporary while waiting moderation
 *  ads:location:LOCATIONID ->id_ad -- store all the ads the location has
 *  ads:language:LANGUAGEID ->id_ad -- store all the ads the language has
 *  ads:published ->id_ad --all the published ads
 *  ads:published:2013-12-21 ->id_ad --- store published id_ad by date
 *  ads:published:2013-12 ->id_ad --- store published id_ad by date
 *  ads:published:2013 ->id_ad --- store published id_ad by date
 *  ads:published:location:LOCATIONID ->id_ad -- store all the published ads the location has
 *  ads:published:language:LANGUAGEID ->id_ad -- store all the published ads the lang has
 *
 *  Sorted sets, all of them have also adhit:KEY:Y-m-d, Only on create, not updated if deleted
 *  ads:id_language = IDLANG+1
 *  ads:id_location = IDLOC+1
 *  ads:id_user = IDUSER+1
 *
 */

class Model_Ad extends Remodel {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'ads';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_ad';

    /**
     * fields that has this model
     * @var array
     */
    protected $_fields = array( 'id_ad',
                                'id_user',
                                'id_language',
                                'title',
                                'description',
                                'description2',
                                'click_url',
                                'display_url',
                                'ip_address',
                                'created',
                                'published',     
                                'displays',      
                                'displays_left', 
                                'status',        
                                'has_image',    
                                );

    /**
     * We create a sorted set to have a leader board. TABLENAME:FIELDNAME -> FIELDVALUE = count+1
     * @var array
     */
     protected $_sorted_sets =  array ( 'id_language',
                                        'id_location',
                                        'id_user',
                                        );

    /**
     * Status constants
     */
    const STATUS_INACTIVE            = 0;    // just posted, not moderated, ads should never have this state
    const STATUS_ACTIVE              = 1;   // published ready to be displayed and moderated
    const STATUS_ACTIVE_FREE         = 10;   // published ready to be displayed and moderated, this ones if are solo in a banner not paid
    const STATUS_IN_MODERATION       = 20;  //waiting to be moderated
    const STATUS_MODERATED           = 50;   //was moderated and not displayed
    const STATUS_FINISH              = 90;   //end of displays

    /**
     * @var  array  Available statuses array
     */
    public static $statuses = array(
        self::STATUS_INACTIVE       =>  'Inactive',
        self::STATUS_ACTIVE         =>  'Published',
        self::STATUS_ACTIVE_FREE    =>  'Published Free',
        self::STATUS_IN_MODERATION  =>  'In Moderation',
        self::STATUS_MODERATED      =>  'Moderated',
        self::STATUS_FINISH         =>  'Finished',
    );


    /**
     * locations array
     * @var array
     */
    protected $_locations = NULL;

    /**
     * sets the locations array
     * @return boolean 
     */
    public function set_locations(array $locations = NULL)
    {
        $this->_locations = $locations;
    }

    /**
     * gets the locations array
     * @return boolean 
     */
    public function locations()
    {
        return $this->_locations;
    }

    /**
     * loads values
     * @param  integer PK to load data  
     * @return boolean
     */
    public function load($pk = NULL)
    {

        if (parent::load($pk))
        {
            $this->set_locations($this->_redis->smembers($this->_table_name.self::KSEP
                                                        .$this->pk().self::KSEP
                                                        .'locations'));
            return TRUE;
        }

        return FALSE;
    }


    /**
     * updates fields an ad, 
     * @param  Redis $pipe usage of pipe to make it faster optional 
     * @return boolean 
     */
    public function update($pipe = NULL)
    {

        $pipe = $this->_redis->pipeline();

        //get the locations before we do anything
        $orig_locations = $this->_redis->smembers($this->_table_name.self::KSEP
                                                        .$this->pk().self::KSEP
                                                        .'locations');
        ksort($orig_locations);//we short them to be sure are the same...

        //get orig language
        $orig_id_language = $this->id_language;

        //if saving was succesfull
        if ($ret = parent::update($pipe))
        {             
            ksort($this->_locations);

            //only if location is different we take measures
            if ($orig_locations != $this->_locations)
            {

                //we need to delete this set 
                $pipe->del($this->_table_name.self::KSEP.$this->pk().self::KSEP.'locations');

                //and remove the id's from the orig sets
                foreach ($orig_locations as $location) 
                {
                    $pipe->srem($this->_table_name.self::KSEP
                                .'location'.self::KSEP
                                .$location,$this->pk());

                    if ($this->status == self::STATUS_ACTIVE OR $this->status == self::STATUS_ACTIVE_FREE)
                        $pipe->srem($this->_table_name.self::KSEP
                                .'published'.self::KSEP
                                .'location'.self::KSEP
                                .$location,$this->pk());
                }

                if (is_array($this->_locations) AND count($this->_locations>0))
                {
                    //set them again
                    $pipe->sadd($this->_table_name.self::KSEP
                                    .$this->pk().self::KSEP
                                    .'locations',$this->_locations);
              
                    foreach ($this->_locations as $location) 
                    {
                        $pipe->sadd($this->_table_name.self::KSEP
                                    .'location'.self::KSEP
                                    .$location,$this->pk());

                        if ($this->status == self::STATUS_ACTIVE OR $this->status == self::STATUS_ACTIVE_FREE)
                            $pipe->sadd($this->_table_name.self::KSEP
                                .'published'.self::KSEP
                                .'location'.self::KSEP
                                .$location,$this->pk());
                    }
                }
                

            }

            //only if different language
            if ($orig_id_language != $this->id_language)
            {
                //removes language
                $pipe->srem($this->_table_name.self::KSEP
                                .'language'.self::KSEP
                                .$this->id_language,$this->pk());

                //adding it again
                $pipe->sadd($this->_table_name.self::KSEP
                                .'language'.self::KSEP
                                .$this->id_language,$this->pk());

                if ($ad->status == self::STATUS_ACTIVE)
                {
                    //removes language
                    $pipe->srem($this->_table_name.self::KSEP
                                .'published'.self::KSEP
                                .'language'.self::KSEP
                                .$this->id_language,$this->pk());
                    //adding it again
                    $pipe->sadd($this->_table_name.self::KSEP
                                .'published'.self::KSEP
                                .'language'.self::KSEP
                                .$this->id_language,$this->pk());
                }

            }

        }

        $pipe->execute();
        return $ret;
    }

    /**
     * creates a new item pk
     * @param  Redis $pipe usage of pipe to make it faster optional 
     * @return boolean 
     */
    public function create($pipe = NULL)
    {
        $this->created = Date::unix2mysql();
        $this->status  = $this::STATUS_INACTIVE;

        $pipe = $this->_redis->pipeline();
        //if saving was succesfull
        if ($ret = parent::create($pipe))
        {            
            //all the locations this ad has
            //ads:X:locations ->id_locations
            $pipe->sadd($this->_table_name.self::KSEP
                            .$this->pk().self::KSEP
                            .'locations',$this->_locations);

            //all the ads with X location
            //ads:location:LOCATIONID ->id_ad
            if (is_array($this->_locations))
            {
                foreach ($this->_locations as $location) 
                {
                    $pipe->sadd($this->_table_name.self::KSEP
                                .'location'.self::KSEP
                                .$location,$this->pk());
                }
            }

            //all the ads with X user
            //ads:user:IDUSER ->id_ad
            $pipe->sadd($this->_table_name.self::KSEP
                            .'user'.self::KSEP
                            .$this->id_user,$this->pk());

            //all the ads with X language
            //ads:language:LANGUAGEID ->id_ad
            $pipe->sadd($this->_table_name.self::KSEP
                            .'language'.self::KSEP
                            .$this->id_language,$this->pk());

            //date sets
            //ads:created:DATE ->id_ad
            $pipe->sadd($this->_table_name.self::KSEP
                            .'created'.self::KSEP
                            .date('Y-m-d'), $this->pk());
            $pipe->sadd($this->_table_name.self::KSEP
                            .'created'.self::KSEP
                            .date('Y-m'), $this->pk());
            $pipe->sadd($this->_table_name.self::KSEP
                            .'created'.self::KSEP
                            .date('Y'), $this->pk());

            //sorted sets
            //example: ads:id_location = IDLOC +1 / ads:id_location:2013/12/25 = IDLOC +1
            foreach ($this->_sorted_sets as $field) 
            {
                if (isset($this->_data[$field]))
                {
                    $pipe->zincrby($this->_table_name.self::KSEP.$field, 1, $this->_data[$field]);
                    $pipe->zincrby($this->_table_name.self::KSEP.$field.self::KSEP.date('Y-m-d'), 1, $this->_data[$field]);
                    $pipe->zincrby($this->_table_name.self::KSEP.$field.self::KSEP.date('Y-m'), 1, $this->_data[$field]);
                    $pipe->zincrby($this->_table_name.self::KSEP.$field.self::KSEP.date('Y'), 1, $this->_data[$field]);
                }
                
            }

        }
        $pipe->execute();

        return $ret;
    }

    /**
     * puts the ad in the moderation queue
     * @return boolean 
     */
    public function to_moderation()
    {
        if ($this->loaded())
        {
            $this->status = self::STATUS_IN_MODERATION;
            $this->save();
            //all ads to moderate
            //ads:moderate ->id_ad @todo with a sort?????
            $this->_redis->sadd($this->_table_name.self::KSEP
                            .'moderate',$this->pk());
        }
    }

    /**
     * change the status of the AD and publish it, normally from moderation
     * @return [type] [description]
     */
    public function publish()
    {
        if ($this->loaded())
        {
            $pipe = $this->_redis->pipeline();

            if ($this->status != self::STATUS_ACTIVE_FREE)
                $this->status = self::STATUS_ACTIVE;

            $this->save($pipe);

            //remove it from moderation
            //ads:moderate ->id_ad
            $pipe->srem($this->_table_name.self::KSEP
                            .'moderate',$this->pk());

            //publish date sets
            $pipe->sadd($this->_table_name.self::KSEP
                            .'published', $this->pk());
            //ads:published:DATE ->id_ad
            $pipe->sadd($this->_table_name.self::KSEP
                            .'published'.self::KSEP
                            .date('Y-m-d'), $this->pk());
            $pipe->sadd($this->_table_name.self::KSEP
                            .'published'.self::KSEP
                            .date('Y-m'), $this->pk());
            $pipe->sadd($this->_table_name.self::KSEP
                            .'published'.self::KSEP
                            .date('Y'), $this->pk());
            
            //all the published ads with X location
            //ads:published:location:LOCATIONID ->id_ad
            foreach ($this->_locations as $location) 
            {
                $pipe->sadd($this->_table_name.self::KSEP
                            .'published'.self::KSEP
                            .'location'.self::KSEP
                            .$location,$this->pk());
            }

            //all the published ads with X language
            //ads:published:language:LANGUAGEID ->id_ad
            $pipe->sadd($this->_table_name.self::KSEP
                            .'published'.self::KSEP
                            .'language'.self::KSEP
                            .$this->id_language,$this->pk());

            $pipe->execute();
        }

    }

    /**
     * change the status of the AD and unpublish it, normally from as event of a hit or from moderation
     * @param  integer $status which status passes
     * @return          
     */
    public function deactivate($status = NULL)
    {
        if ($this->loaded())
        {
            if ($status!==NULL) $status = self::STATUS_FINISH;
            $pipe = $this->_redis->pipeline();
            $this->status = $status;
            $this->save($pipe);

            //WE REMOVE FROM SAME SETS AS IN PUBLISH
            $pipe->srem($this->_table_name.self::KSEP
                            .'published', $this->pk());
            //publish date sets
            //ads:published:DATE ->id_ad
            $pipe->srem($this->_table_name.self::KSEP
                            .'published'.self::KSEP
                            .date('Y-m-d'), $this->pk());
            $pipe->srem($this->_table_name.self::KSEP
                            .'published'.self::KSEP
                            .date('Y-m'), $this->pk());
            $pipe->srem($this->_table_name.self::KSEP
                            .'published'.self::KSEP
                            .date('Y'), $this->pk());
            
            //all the published ads with X location
            //ads:published:location:LOCATIONID ->id_ad
            foreach ($this->_locations as $location) 
            {
                $pipe->srem($this->_table_name.self::KSEP
                            .'published'.self::KSEP
                            .'location'.self::KSEP
                            .$location,$this->pk());
            }

            //all the published ads with X language
            //ads:published:language:LANGUAGEID ->id_ad
            $pipe->srem($this->_table_name.self::KSEP
                            .'published'.self::KSEP
                            .'language'.self::KSEP
                            .$this->id_language,$this->pk());

            $pipe->execute();
        }
    }

    /**
     * deletes an AD key from the redis @todo, should we allow to delete? or just change the status?
     * @param  integer $pk optional
     * @return boolean      
     */
    public function delete($pk = NULL)
    {
        //we only allow delete if its same pk
        $pk = $this->pk();

        if ($this->loaded())
        {
            //we delete if was published
            $this->deactivate();

            $pipe = $this->_redis->pipeline();

            //MASS DESTRUCTIONS!!! WARNING!
            //all ads to moderate
            //ads:moderate ->id_ad
            $pipe->srem($this->_table_name.self::KSEP
                            .'moderate',$this->pk());

            //all the locations this ad has, remove the key, since is unique to this ad
            //ads:X:locations ->id_locations
            $pipe->del($this->_table_name.self::KSEP.$this->pk().self::KSEP.'locations');

            //all the ads with X location
            //ads:location:LOCATIONID ->id_ad
            foreach ($this->_locations as $location) 
            {
                $pipe->srem($this->_table_name.self::KSEP
                            .'location'.self::KSEP
                            .$location,$this->pk());
            }

            //all the ads with X user
            //ads:user:LANGUAGEID ->id_ad
            $pipe->srem($this->_table_name.self::KSEP
                            .'user'.self::KSEP
                            .$this->id_user,$this->pk());

            //all the ads with X language
            //ads:language:LANGUAGEID ->id_ad
            $pipe->srem($this->_table_name.self::KSEP
                            .'language'.self::KSEP
                            .$this->id_language,$this->pk());

            //date sets
            //ads:created:DATE ->id_ad
            $pipe->srem($this->_table_name.self::KSEP
                            .'created'.self::KSEP
                            .date('Y-m-d'), $this->pk());
            $pipe->srem($this->_table_name.self::KSEP
                            .'created'.self::KSEP
                            .date('Y-m'), $this->pk());
            $pipe->srem($this->_table_name.self::KSEP
                            .'created'.self::KSEP
                            .date('Y'), $this->pk());

            $pipe->execute();
        }
        return parent::delete($pk);
 
    }

    /**
     * hits the AD
     * @param  Model_Adhit $adhit  
     */
    public function hit(Model_Adhit $adhit)
    {
        if ($this->loaded())
        {
            //all the hits this AD have, ads:IDAD:hits ->id_adhit
            $this->_redis->sadd($this->_table_name.self::KSEP
                        .$this->pk().self::KSEP
                        .'hits',$adhit->id_adhit);

            //@todo future if type is CPC do not deactivate/decrease!
            
            //decrease the displays
            $this->_redis->hincrby($this->_table_name.self::KSEP.$this->pk(), 'displays_left',-1);

            //after decrease if 0 deactivate:
            if ($this->_redis->hget($this->_table_name.self::KSEP.$this->pk(), 'displays_left') <= 0)
            {
                //notify to user not more credit, send email
                $user  = new Model_User($this->id_user);
                
                $url_renew = $user->ql('default',array('controller'=>'ads','action'=>'credit','id'=>$this->id_ad),FALSE);

                $user->email('ad.expired.renew',array('[URL.RENEW]'=>$url_renew));

                $this->deactivate(self::STATUS_FINISH);
            }
        }  
    }

    /**
     * clicks the AD
     * @param  integer $adhit  
     */
    public function click($adhit = NULL)
    {
        if ($this->loaded() AND is_numeric($adhit))
        {
            //get the hit which the click was made
            $adhit = new Model_Adhit(NULL,NULL,$adhit);

            if ($adhit->loaded())
            {
                //all the hits/clicks AD have ads:IDAD:clicks ->id_adhit
                $command = $this->_table_name.self::KSEP.$this->pk().self::KSEP.'clicks';

                //check that the click does not exists, so is unique!
                if (! $this->_redis->sismember($command,$adhit->id_adhit))
                {
                    //not exists create
                    $this->_redis->sadd($command,$adhit->id_adhit);

                    //new click
                    $click = new Model_Adclick();
                    $click->values($adhit->values());//setting same values as hit
                    $click->id_ad = $this->id_ad;//but we store the ad ;)
                    $click->create();

                    //@todo future, discount from credit?
                }  
                
            }
            
        }  
    }

    /**
     * retrieves the ads from geo and language
     * @param  array $geo         geoip array
     * @param  integer $language_id id_language
     * @param  Model_Adformat $adformat     adformat
     * @return array      with id_ads in it        
     */
    public function find($geo,$language_id,$adformat,$only_text = FALSE)
    {

        //1 we search for banners with adformat, language and location
            // we do a random and return only 1


        //if not found any banner we search for text ads

        $num_ads    = $adformat->max_ad_slots;
        $ads        = array();
        $ads_city   = array();//ads city + lang
        $ads_country= array();//ads country + lang
        $ads_lang   = array();//ads only by lang

        //search ads same city and language
        $ads_city = $this->_redis->sinter(array($this->_table_name.self::KSEP .'published'.self::KSEP.'location'.self::KSEP.$geo['id_city'],
                                    $this->_table_name.self::KSEP .'published'.self::KSEP.'language'.self::KSEP.$language_id,));

        //did we succeed?
        if (count($ads_city < $num_ads))
        {
            //no :( search by ads same country and language
            $ads_country = $this->_redis->sinter(array($this->_table_name.self::KSEP .'published'.self::KSEP.'location'.self::KSEP.$geo['id_country'],
                                    $this->_table_name.self::KSEP .'published'.self::KSEP.'language'.self::KSEP.$language_id,));

        }

        //city  + country (country can be empty)
        $ads = array_unique(array_merge($ads_city,$ads_country));

        //do we have enough ads? no!!! get them random by language
        if (count($ads) < $num_ads)
        {
            $left = $num_ads - count($ads);
            $ads_lang = $this->_redis->srandmember($this->_table_name.self::KSEP .'published'.self::KSEP.'language'.self::KSEP.$language_id, $left);
            $ads = array_unique(array_merge($ads,$ads_lang));
        }

        //do we have enough ads? fill with anything...:( @todo publish
        if (count($ads) < $num_ads)
        {
            $left = $num_ads - count($ads);
            $ads_any = $this->_redis->srandmember($this->_table_name.self::KSEP.'published', $left);
            $ads = array_unique(array_merge($ads,$ads_any));
        }

        //we need only few randomly
        $ads = Arr::random($ads, $num_ads);

        return $ads;
    }


    /**
     * retrieve the PK for the list of the given user
     * @param integer $id_user
     * @param  integer $start from
     * @param  integer $stop  to
     * @return array 
     */
    public function get_ads_user($id_user,$start = 0, $stop = 9)
    {
        return $this->_redis->sort($this->_table_name.self::KSEP.'user'.self::KSEP.$id_user,array('by'=> 'nosort','limit' => array($start,$stop)));
    }

    /**
     * retrieve the PK for the list of the given user
     * @param string $filter = moderate, published....
     * @param  integer $start from
     * @param  integer $stop  to
     * @return array 
     */
    public function get_ads($filter = 'moderate', $start = 0, $stop = 9)
    {
        return $this->_redis->sort($this->_table_name.self::KSEP.$filter,array('by'=> 'nosort','limit' => array($start,$stop)));
    }



   /**
    * get the order for this ad
    * @param  int $status status from Model_Order
    * @return Model_Order         
    */
    public function order($status = NULL)
    {
        if ($this->loaded())
        {
            $order = new Model_Order();

            $order->where('id_ad',   '=', $this->id_ad)
                  ->where('id_user', '=', $this->id_user);

            if ($status!=NULL)
                $order->where('status', '=', $status);

            $order->limit(1)->find();
            return $order;
        }
        else
            return FALSE;
    }

}