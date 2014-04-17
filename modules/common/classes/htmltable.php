<?php 
/**
 * HTML Table helper class. Provides table formatting method
 *
 * @package    OC
 * @category   Common
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2012 AdSerum.com
 * @license    GPL v3
 */
class HTMLTable {

	/**
	 * Formats a multidimensional array as table
	 * 
	 * @param   array  data
	 * @return  string
	 */
	public static function encode($data)
	{
		$out = '<table>'.PHP_EOL;
		$firstrow = 
	    $fieldnames = array_keys($data[0]);
		
		// Headers
		$out .= '<tr>';
		foreach( $data[0] as $fieldname=>$val)
		{
			$out .= '<th>'.$fieldname.'</th>';
		}
		$out .= '</tr>'.PHP_EOL;
		
		foreach ($data as $row)
		{
			$out .= '<tr>';
			foreach ($row as $field)
			{
				$out .= '<td>'.$field.'</td>';
			}
			$out .= '</tr>'.PHP_EOL;
		}
		//$out .= '</table>';
		
		return $out;
	}
	
} // End num
