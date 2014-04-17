<?php 

class Controller_Content extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('seotitle','id_language','type');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'content';

	
}
