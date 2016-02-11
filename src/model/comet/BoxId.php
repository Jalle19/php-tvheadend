<?php

namespace Jalle19\tvheadend\model\comet;

/**
 * Represents a "boxid" string which is used when using the comet polling
 * mechanism. tvheadend purges unused boxids periodically so the object
 * keeps track of how long ago it was used. A new boxid instance should
 * be generated if it gets too old.
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
class BoxId
{

	/**
	 * @var string the boxid
	 */
	private $_boxId;

	/**
	 * @var int the timestamp of when this boxid was last used
	 */
	private $_lastUsed;


	/**
	 * @param string $boxId the boxid
	 */
	public function __construct($boxId)
	{
		$this->_boxId    = $boxId;
		$this->_lastUsed = time();
	}


	/**
	 * @return string
	 */
	public function getBoxId()
	{
		// Update last used
		$this->_lastUsed = time();

		return $this->_boxId;
	}


	/**
	 * @return int the number of seconds since this boxid was last used
	 */
	public function getAge()
	{
		return time() - $this->_lastUsed;
	}

}
