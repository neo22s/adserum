<?php 
/**
 * Core class for OC, contains commons functions and helpers
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */

class Core {
	
	/**
	 * 
	 * OC version
	 * @var string
	 */
	const version = 'Alpha';
	
	/**
	 * 
	 * Initializes configs for the APP to run
	 */
	public static function initialize()
	{	
		
		// Strip HTML from all request variables
		$_GET    = Core::strip_tags($_GET);
		$_POST   = Core::strip_tags($_POST);
		$_COOKIE = Core::strip_tags($_COOKIE);
		
		/**
		 * Load all the configs from DB
		 */
		//Change the default cache system, based on your config /config/common.php 
		Cache::$default = Core::config('common.cache');
		//is not loaded yet in Kohana::$config
		//Kohana::$config->attach(new Config(), FALSE);
		
		//overwrite default Kohana init configs.
		Kohana::$base_url = Core::config('site.url');
		
		//enables friendly url
		Kohana::$index_file = FALSE;
		//cookie salt for the app
		Cookie::$salt = Core::config('auth.cookie_salt');
		
		// -- i18n Configuration and initialization -----------------------------------------
		I18n::initialize();
		
		//getting the selected theme, and loading defaults
		View::initialize();
		
		//Loading the OC Routes
		if (($init_routes = Kohana::find_file('config','routes')))
		{
			require_once $init_routes[0];//returns array of files but we need only 1 file
		}
	}
	
	/**
	 * Recursively strip html tags an input variable:
	 *
	 * @param   mixed  any variable
	 * @param   string  HTML tags
	 * @return  mixed  sanitized variable
	 */
	public static function strip_tags($value,$allowable_tags=NULL)
	{
		if ($allowable_tags===NULL) $allowable_tags = Core::config('site.allowable_tags');
		
		if (is_array($value) OR is_object($value))
		{
			foreach ($value as $key => $val)
			{
				// Recursively strip each value
				$value[$key] = Core::strip_tags($val,$allowable_tags);
			}
		}
		elseif (is_string($value))
		{
			$value = strip_tags($value,$allowable_tags);
		}
	
		return $value;
	}

	/**
     * Shortcut to load a group of configs
     * @param type $group
     * @return array 
     */
    public static function config($group)
    {
    	return Kohana::$config->load($group);
    }
    
	/**
     * write to file
     * @param $filename fullpath file name
     * @param $content
     * @return boolean
     */
    public static function fwrite($filename,$content,$mode = 'w')
    {
        $file = fopen($filename, $mode);
	    if ($file)
	    {//able to create the file
	        fwrite($file, $content);
	        fclose($file);
	        return TRUE;
	    }
	    return FALSE;   
    }
    
    /**
     * read file content
     * @param $filename fullpath file name
     * @return $string or false if not found
     */
    public static function fread($filename)
    {
        if (is_readable($filename))
        {
            $file = fopen($filename, 'r');
    	    if ($file)
    	    {//able to read the file
    	        $data = fread($file, filesize($filename));
    		    fclose($file);
    	        return $data;
    	    }
        }
	    return FALSE;   
    }
    
    /**
     * shortcut for the query method $_GET
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function get($key,$default=NULL)
    {
    	return (Request::current()->query($key)!==NULL)?Request::current()->query($key):$default;
    }

    /**
     * shortcut for $_POST[]
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function post($key,$default=NULL)
    {
    	return (Request::current()->post($key)!==NULL)?Request::current()->post($key):$default;
    }

    /**
     * shortcut to get or post
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function request($key,$default=NULL)
    {
        return (Core::post($key)!==NULL)?Core::post($key):Core::get($key,$default);
    }

    /**
     * visitor is a bot?
     * @return boolean
     */
    public static function is_bot() 
    {
	    $spiders = array(
	        "abot","dbot","ebot","hbot","kbot","lbot","mbot","nbot","obot","pbot","rbot","sbot","tbot","vbot","ybot","zbot","bot.","bot/","_bot",".bot","/bot","-bot",":bot","(bot","crawl","slurp","spider","seek","accoona","acoon","adressendeutschland","ah-ha.com","ahoy","altavista","ananzi","anthill","appie","arachnophilia","arale","araneo","aranha","architext","aretha","arks","asterias","atlocal","atn","atomz","augurfind","backrub","bannana_bot","baypup","bdfetch","big brother","biglotron","bjaaland","blackwidow","blaiz","blog","blo.","bloodhound","boitho","booch","bradley","butterfly","calif","cassandra","ccubee","cfetch","charlotte","churl","cienciaficcion","cmc","collective","comagent","combine","computingsite","csci","curl","cusco","daumoa","deepindex","delorie","depspid","deweb","die blinde kuh","digger","ditto","dmoz","docomo","download express","dtaagent","dwcp","ebiness","ebingbong","e-collector","ejupiter","emacs-w3 search engine","esther","evliya celebi","ezresult","falcon","felix ide","ferret","fetchrover","fido","findlinks","fireball","fish search","fouineur","funnelweb","gazz","gcreep","genieknows","getterroboplus","geturl","glx","goforit","golem","grabber","grapnel","gralon","griffon","gromit","grub","gulliver","hamahakki","harvest","havindex","helix","heritrix","hku www octopus","homerweb","htdig","html index","html_analyzer","htmlgobble","hubater","hyper-decontextualizer","ia_archiver","ibm_planetwide","ichiro","iconsurf","iltrovatore","image.kapsi.net","imagelock","incywincy","indexer","infobee","informant","ingrid","inktomisearch.com","inspector web","intelliagent","internet shinchakubin","ip3000","iron33","israeli-search","ivia","jack","jakarta","javabee","jetbot","jumpstation","katipo","kdd-explorer","kilroy","knowledge","kototoi","kretrieve","labelgrabber","lachesis","larbin","legs","libwww","linkalarm","link validator","linkscan","lockon","lwp","lycos","magpie","mantraagent","mapoftheinternet","marvin/","mattie","mediafox","mediapartners","mercator","merzscope","microsoft url control","minirank","miva","mj12","mnogosearch","moget","monster","moose","motor","multitext","muncher","muscatferret","mwd.search","myweb","najdi","nameprotect","nationaldirectory","nazilla","ncsa beta","nec-meshexplorer","nederland.zoek","netcarta webmap engine","netmechanic","netresearchserver","netscoop","newscan-online","nhse","nokia6682/","nomad","noyona","nutch","nzexplorer","objectssearch","occam","omni","open text","openfind","openintelligencedata","orb search","osis-project","pack rat","pageboy","pagebull","page_verifier","panscient","parasite","partnersite","patric","pear.","pegasus","peregrinator","pgp key agent","phantom","phpdig","picosearch","piltdownman","pimptrain","pinpoint","pioneer","piranha","plumtreewebaccessor","pogodak","poirot","pompos","poppelsdorf","poppi","popular iconoclast","psycheclone","publisher","python","rambler","raven search","roach","road runner","roadhouse","robbie","robofox","robozilla","rules","salty","sbider","scooter","scoutjet","scrubby","search.","searchprocess","semanticdiscovery","senrigan","sg-scout","shai'hulud","shark","shopwiki","sidewinder","sift","silk","simmany","site searcher","site valet","sitetech-rover","skymob.com","sleek","smartwit","sna-","snappy","snooper","sohu","speedfind","sphere","sphider","spinner","spyder","steeler/","suke","suntek","supersnooper","surfnomore","sven","sygol","szukacz","tach black widow","tarantula","templeton","/teoma","t-h-u-n-d-e-r-s-t-o-n-e","theophrastus","titan","titin","tkwww","toutatis","t-rex","tutorgig","twiceler","twisted","ucsd","udmsearch","url check","updated","vagabondo","valkyrie","verticrawl","victoria","vision-search","volcano","voyager/","voyager-hc","w3c_validator","w3m2","w3mir","walker","wallpaper","wanderer","wauuu","wavefire","web core","web hopper","web wombat","webbandit","webcatcher","webcopy","webfoot","weblayers","weblinker","weblog monitor","webmirror","webmonkey","webquest","webreaper","websitepulse","websnarf","webstolperer","webvac","webwalk","webwatch","webwombat","webzinger","wget","whizbang","whowhere","wild ferret","worldlight","wwwc","wwwster","xenu","xget","xift","xirq","yandex","yanga","yeti","yodao","zao/","zippp","zyborg",
	        
	        "Teoma", "alexa", "froogle", "Gigabot", "inktomi","looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory","Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
    		"crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp","msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
    		"Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot","Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","TweetmemeBot","Butterfly","Twitturls","Me.dium","Twiceler"
	    );

		//If the spider text is found in the current user agent, then return true
	    foreach($spiders as $spider) 
	        if ( stripos($_SERVER['HTTP_USER_AGENT'], $spider) !== FALSE ) return TRUE;
	    
    	//If it gets this far then no bot was found!
    	return FALSE;
	}

} //end core

/**
 * Common functions
 */


/**
 *
 * Dies and var_dumps
 * @param any $var
 */
function d($var)
{
	die(var_dump($var));
}