<?php

namespace Jalle19\tvheadend\model\network;

use Jalle19\tvheadend\exception;

/**
 * Factory for creating network objects from API responses
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
class Factory
{

	/**
	 * Prevent instantiation
	 */
	private function __construct()
	{

	}

	/**
	 * Factory method
	 * @param string $className the network type (class)
	 * @return Network
	 * @throws exception\NotImplementedException
	 */
	public static function factory($className)
	{
		switch ($className)
		{
			case Network::CLASS_IPTV:
				return new IptvNetwork();
			default:
				throw new exception\NotImplementedException();
		}
	}


	/**
	 * Factory method when dealing with raw entries
	 * @param string $className the network type (class)
	 * @param \stdClass $entry the network entry
	 * @return Network
	 * @throws exception\NotImplementedException
	 */
	public static function fromRawEntry($className, $entry)
	{
		switch ($className)
		{
			case Network::CLASS_IPTV:
				return IptvNetwork::fromRawEntry($entry);
			default:
				throw new exception\NotImplementedException();
		}
	}

}
