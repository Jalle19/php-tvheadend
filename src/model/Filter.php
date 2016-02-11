<?php

namespace Jalle19\tvheadend\model;

/**
 * Represents a grid filter
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
class Filter implements \JsonSerializable
{
	
	/**
	 * @var \stdClass the filter definition
	 */
	private $_filter = array();

	/**
	 * Adds a new filter definition
	 * @param string $type the filter type
	 * @param mixed $value the filter value
	 * @param string $field the field/column the filter applies to
	 */
	public function addDefinition($type, $value, $field)
	{
		$definition = new \stdClass();
		$definition->type = $type;
		$definition->value = $value;
		$definition->field = $field;
		
		$this->_filter[] = $definition;
	}

	/**
	 * @inheritdoc
	 */
	public function jsonSerialize()
	{
		return $this->_filter;
	}

}
