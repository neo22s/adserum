<?php 

class Controller_Crontab extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('name','period','callback','active');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'crontab';

	
}
