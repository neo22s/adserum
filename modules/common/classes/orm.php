<?php 
/**
 * Extended functionality for ORM
 *
 * @package    OC
 * @category   Model
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2012 AdSerum.com
 * @license    GPL v3
 */

class ORM extends Kohana_ORM {

	/**
	 * Name of the database to use
	 *
	 * @access	protected
	 * @var		string	$_db default [default]
	 */
	protected $_db = 'default';

	/**
	 * @var  bool  Post Filtered Flag
	 */
	protected $_post_filtered = FALSE;


	/**
	 * Returns the values of this object as a JSON array, including any related
	 * one-one models that have already been loaded using with()
	 *
	 * @return  string  JSON formatted
	 */
	public function as_json()
	{
		$out = parent::as_array();

		return json_encode($out);
	}


	/**
	 * Handles retrieval of all model values, relationships, and metadata.
	 *
	 * @param   string $column Column name
	 * @return  mixed
	 */
	public function __get($column)
	{
		$value = parent::__get($column);

		//check if we need to aply anything to this value
		$value = $this->run_load_filter($column, $value);

		return $value;
	}


	/**
	 * Insert a new object to the database
	 * @param  Validation $validation Validation object
	 * @return ORM
	 */
	public function create(Validation $validation = NULL)
	{
		if ($this->_loaded)
		throw new Kohana_Exception('Cannot create :model model because it is already loaded.', array(':model' => $this->_object_name));

		// Require model validation before saving
		if ( ! $this->_valid)
		{
			$this->check($validation);
		}

		$data = array();
		foreach ($this->_changed as $column)
		{
			// Generate list of column => values
			if ( ! $this->_post_filtered)
			{
				$data[$column] = $this->run_save_filter($column, $this->_object[$column]);
			}
			else
			{
				$data[$column] = $this->_object[$column];
			}
		}
		$this->_post_filtered = TRUE;


		if (is_array($this->_created_column))
		{
			// Fill the created column
			$column = $this->_created_column['column'];
			$format = $this->_created_column['format'];

			$data[$column] = $this->_object[$column] = ($format === TRUE) ? time() : date($format);
		}

		$result = DB::insert($this->_table_name)
		->columns(array_keys($data))
		->values(array_values($data))
		->execute($this->_db);

		if ( ! array_key_exists($this->_primary_key, $data))
		{
			// Load the insert id as the primary key if it was left out
			$this->_object[$this->_primary_key] = $this->_primary_key_value = $result[0];
		}

		// Object is now loaded and saved
		$this->_loaded = $this->_saved = TRUE;

		// All changes have been saved
		$this->_changed = array();
		$this->_original_values = $this->_object;

		return $this;
	}

	/**
	 * Updates a single record or multiple records
	 *
	 * @chainable
	 * @param  Validation $validation Validation object
	 * @return ORM
	 */
	public function update(Validation $validation = NULL)
	{
		if ( ! $this->_loaded)
		throw new Kohana_Exception('Cannot update :model model because it is not loaded.', array(':model' => $this->_object_name));

		if (empty($this->_changed))
		{
			// Nothing to update
			return $this;
		}

		// Require model validation before saving
		if ( ! $this->_valid)
		{
			$this->check($validation);
		}

		$data = array();
		foreach ($this->_changed as $column)
		{
			// Compile changed data
			if ( ! $this->_post_filtered)
			{
				$data[$column] = $this->run_save_filter($column, $this->_object[$column]);
			}
			else
			{
				$data[$column] = $this->_object[$column];
			}
		}
		$this->_post_filtered = TRUE;

		if (is_array($this->_updated_column))
		{
			// Fill the updated column
			$column = $this->_updated_column['column'];
			$format = $this->_updated_column['format'];

			$data[$column] = $this->_object[$column] = ($format === TRUE) ? time() : date($format);
		}

		// Use primary key value
		$id = $this->pk();

		// Update a single record
		DB::update($this->_table_name)
		->set($data)
		->where($this->_primary_key, '=', $id)
		->execute($this->_db);

		if (isset($data[$this->_primary_key]))
		{
			// Primary key was changed, reflect it
			$this->_primary_key_value = $data[$this->_primary_key];
		}

		// Object has been saved
		$this->_saved = TRUE;

		// All changes have been saved
		$this->_changed = array();
		$this->_original_values = $this->_object;

		return $this;
	}

	/**
	 * Save -Filters a value for a specific column after validating them
	 *
	 * @param  string $field  The column name
	 * @param  string $value  The value to filter
	 * @return string
	 */
	protected function run_save_filter($field, $value)
	{
		$filters = $this->save_filters();
		return $this->run_filters($field, $value, $filters);
	}

	/**
	 * Save Filters definitions. After validation
	 *
	 * @return array
	 */
	public function save_filters()
	{
		return array();
	}


	/**
	 * Load-Filters a value for a specific column
	 * This is used every time a field is trying to be __get()
	 *
	 * @param  string $field  The column name
	 * @param  string $value  The value to filter
	 * @return string
	 */
	protected function run_load_filter($field, $value)
	{
		$filters = $this->load_filters();
		return $this->run_filters($field, $value, $filters);
	}

	/**
	 * load filters definitions
	 *
	 * @return array
	 */
	public function load_filters()
	{
		return array();
	}


	/**
	 * Filters a value for a specific column
	 *
	 * @param  string $field  The column name
	 * @param  string $value  The value to filter
	 * @param  array  $filters filters from the model
	 * @return string
	 */
	protected function run_filters($field, $value, $filters)
	{
		//check we get filters
		if (is_array($filters))
		{
			//we have a filter for this field????
			if(key_exists($field, $filters))
			{
				// Get the filters for this column
				$wildcards = empty($filters[TRUE]) ? array() : $filters[TRUE];

				// Merge in the wildcards
				$filters = empty($filters[$field]) ? $wildcards : array_merge($wildcards, $filters[$field]);

				// Bind the field name and model so they can be used in the filter method
				$_bound = array
				(
                        ':field' => $field,
                        ':model' => $this,
				);

				foreach ($filters as $array)
				{
					// Value needs to be bound inside the loop so we are always using the
					// version that was modified by the filters that already ran
					$_bound[':value'] = $value;

					// Filters are defined as array($filter, $params)
					$filter = $array[0];
					$params = Arr::get($array, 1, array(':value'));

					foreach ($params as $key => $param)
					{
						if (is_string($param) AND array_key_exists($param, $_bound))
						{
							// Replace with bound value
							$params[$key] = $_bound[$param];
						}
					}

					if (is_array($filter) OR ! is_string($filter))
					{
						// This is either a callback as an array or a lambda
						$value = call_user_func_array($filter, $params);
					}
					elseif (strpos($filter, '::') === FALSE)
					{
						// Use a function call
						$function = new ReflectionFunction($filter);

						// Call $function($this[$field], $param, ...) with Reflection
						$value = $function->invokeArgs($params);
					}
					else
					{
						// Split the class and method of the rule
						list($class, $method) = explode('::', $filter, 2);

						// Use a static method call
						$method = new ReflectionMethod($class, $method);

						// Call $Class::$method($this[$field], $param, ...) with Reflection
						$value = $method->invokeArgs(NULL, $params);
					}

				}//for each filter

			}//end if key exists

		}// if array exists

		return $value;
	}

	/**
	 * Override from parent.
	 * Filters a value for a specific column, before the validation
	 *
	 * @param  string $field  The column name
	 * @param  string $value  The value to filter
	 * @return string
	 */
	protected function run_filter($field, $value)
	{
		$filters = $this->filters();
		return $this->run_filters($field, $value, $filters);
	}
	 

	/**
	 *
	 *  Mark object as loaded, perfect for when the data comes from another source
	 */
	public function mark_as_loaded()
	{
		$this->_loaded = TRUE;
	}

	/**
	 * 
	 * formo definitions
	 * 
	 */
	public function form_setup($form)
	{
		
	}
	
	/**
	 * exclude this fields from the form
	 * @return array 
	 */
	public function exclude_fields()
	{
	   return array($this->_primary_key);
	}

	/**
	 * Count the number of records in the table.
	 *
	 * @return integer
	 */
	public function count_all()
	{
		$selects = array();

		foreach ($this->_db_pending as $key => $method)
		{
			if ($method['name'] == 'select')
			{
				// Ignore any selected columns for now
				$selects[] = $method;
				unset($this->_db_pending[$key]);
			}
		}

		if ( ! empty($this->_load_with))
		{
			foreach ($this->_load_with as $alias)
			{
				// Bind relationship
				$this->with($alias);
			}
		}

		$this->_build(Database::SELECT);

		$records = $this->_db_builder->from(array($this->_table_name, $this->_object_name))
			->select(array('COUNT("'.$this->_primary_key.'")', 'records_found'))
			->execute($this->_db)
			->get('records_found');

		// Add back in selected columns
		$this->_db_pending += $selects;

		//$this->reset();
				
		// Return the total number of records in a table
		return $records;
	}


}