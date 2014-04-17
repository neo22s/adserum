<?php 
class Controller_Language extends Auth_Crud {



	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('language_name','charset','locale');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'language';


}
