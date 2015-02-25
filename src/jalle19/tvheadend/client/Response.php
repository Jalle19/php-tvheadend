<?php

namespace jalle19\tvheadend\client;

/**
 * Represents an API response
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
class Response
{

	/**
	 * @var string the raw response content
	 */
	private $_content;

	/**
	 * Class constructor
	 * @param string $content the response content
	 */
	public function __construct($content)
	{
		$this->_content = $content;
	}

	/**
	 * @return string the content
	 */
	public function getContent()
	{
		return $this->_content;
	}

}
