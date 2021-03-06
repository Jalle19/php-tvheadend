<?php

namespace Jalle19\tvheadend\model\network;

/**
 * Represents an IPTV network
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
 * @property int $max_streams
 * @property int $max_bandwidth
 * @property int $max_timeout
 * @property int $priority;
 * @property int $spriority
 */
class IptvNetwork extends Network
{

	public function getClassName()
	{
		return Network::CLASS_IPTV;
	}

	protected function getDefaultProperties()
	{
		return array_merge(parent::getDefaultProperties(), array(
			'max_streams'=>0,
			'max_bandwidth'=>0,
			'max_timeout'=>15,
			'priority'=>1,
			'spriority'=>1));
	}

}
