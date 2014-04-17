<?php 
/**
* I18n class for php-gettext
*
* @package    I18n
* @category   Translations
* @author     Chema <chema@garridodiaz.com>
* @copyright  (c) 2012 AdSerum.com
* @license    GPL v3
*/

//Initialization



class I18n extends Kohana_I18n {

    public static $locale;
    public static $charset;
    public static $domain;
    /**
     * forces to use the dropin
     */
    public static $dropin = FALSE;

    /**
     * 
     * Initializes the php-gettext
     * Remember to load first php-gettext
	 *
     */
    public static function initialize()
    {        	
        //time zone set in the config
        date_default_timezone_set(Core::config('common.timezone'));
        
        //Kohana core charset, used in the HTML templates as well
        Kohana::$charset  = Core::config('common.charset');
    }    
    
    /**
     * 
     * Initializes the php-gettext
     * Remember to load first php-gettext
     * @param string $locale
     * @param string $charset
     * @param string $domain
     */
    public static function gettext($locale = 'en_US',$charset = 'utf-8', $domain = 'messages')
    {     
        /**
         * setting the statics so later we can access them from anywhere
         */
        self::$locale  = $locale;
        self::$charset = $charset;
        self::$domain  = $domain;
        
        Kohana::$charset = self::$charset;
        i18n::lang(self::$locale);
                
        /**
         * In Windows LC_MESSAGES are not recognized by any reason.
         * So we check if LC_MESSAGES is defined to avoid bugs,
         * and force using gettext
         */
        if(defined('LC_MESSAGES'))
            $locale_res = setlocale(LC_MESSAGES, self::$locale);
        else
            $locale_res = FALSE;


        /**
         * check if gettext exists if not uses gettext dropin
         */
        if ( !function_exists('_') OR $locale_res===FALSE OR empty($locale_res) )
        {
            /**
             * gettext override
             * v 1.0.11
             * https://launchpad.net/php-gettext/
             * We load php-gettext here since Kohana_I18n tries to create the function __() function when we extend it.
             * PHP-gettext already does this.
             */
            require Kohana::find_file('vendor', 'php-gettext/gettext','inc'); 
            
            T_setlocale(LC_MESSAGES, self::$locale);
            T_bindtextdomain(self::$domain,APPMODPATH.'common/languages');
            T_bind_textdomain_codeset(self::$domain, self::$charset);
            T_textdomain(self::$domain);

            //force to use the gettext dropin
            self::$dropin = TRUE;
            
        }
        /**
         * gettext exists using fallback in case locale doesn't exists
         */
        else
        {

            bindtextdomain(self::$domain,APPMODPATH.'common/languages');
            bind_textdomain_codeset(self::$domain, self::$charset);
            textdomain(self::$domain);
        }
       
            
    }  
    
    
    /**
     * 
     * Override normal translate
     * @param string $string to translate
     * @param string $lang does nothing, legacy
     */
    public static function get($string, $lang = NULL)
    {
        //using the gettext dropin forced
        if (self::$dropin === TRUE)
            return _gettext($string);
        else
            return _($string);
    }
    
}//end i18n


/**
 *
 * echo for the translation
 * @param string $string
 * @return string
 */
function _e($string)
{
    echo T_($string);
}