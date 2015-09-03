<?php

namespace jalle19\tvheadend\model;

/**
 * Base class for all API object models
 * 
 * Copyright (C) 2015 Sam Stenvall
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
abstract class Node implements \JsonSerializable
{

	/**
	 * @var array the dynamic object configuration
	 */
	protected $_properties;

	/**
	 * @return stdClass a JSON representation of this object
	 */
	public function jsonSerialize()
	{
		return $this->_properties;
	}

	/**
	 * Magic getter
	 * @param string $name
	 * @return mixed the specified property, or null if the property doesn't exist
	 */
	public function __get($name)
	{
		if (isset($this->_properties[$name]))
			return $this->_properties[$name];
		else
			return null;
	}

	/**
	 * Magic setter
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		$this->_properties[$name] = $value;
	}

	/**
	 * Constructs an appropriate object based on the raw entry
	 * @param stdClass $entry the raw entry
	 * @return \jalle19\tvheadend\model\className
	 */
	public static function fromRawEntry($entry)
	{
		$className = get_called_class();
		$class = new $className();

		foreach ($entry as $property=> $value)
			$class->{$property} = $value;

		return $class;
	}

}
