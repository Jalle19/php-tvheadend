<?php

namespace jalle19\tvheadend\client;

use jalle19\tvheadend\model;

/**
 * Represents an API request
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
class Request
{

	/**
	 * @var string the request path
	 */
	private $_path;

	/**
	 * @var array the request parameters
	 */
	private $_parameters;

	/**
	 * Class constructor
	 * @param string $path
	 * @param array $parameters
	 */
	public function __construct($path, $parameters = array())
	{
		$this->_path = $path;
		$this->_parameters = $parameters;
	}

	/**
	 * @return string the path
	 */
	public function getPath()
	{
		return $this->_path;
	}

	/**
	 * @return array the parameters
	 */
	public function getParameters()
	{
		return $this->_parameters;
	}
	
	/**
	 * Adds the specified filter to the request
	 * @param \jalle19\tvheadend\model\Filter $filter
	 */
	public function setFilter(model\Filter $filter)
	{
		$this->_parameters['filter'] = json_encode($filter);
	}

}
