<?php 
class Controller_User extends Auth_Crud {



	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('name','email','status');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'user';




}
