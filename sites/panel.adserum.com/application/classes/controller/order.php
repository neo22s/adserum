<?php 

class Controller_Order extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_order','amount','status');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'order';

	/**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var public
     */
   //array $crud_actions = array('update','delete');
}
