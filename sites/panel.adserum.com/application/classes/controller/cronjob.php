<?php 

class Controller_Cronjob extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_cron','date_start','date_finished');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'cronjob';

	
}
