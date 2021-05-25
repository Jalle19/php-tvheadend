<?php

namespace Jalle19\tvheadend\model\multiplex;

use Jalle19\tvheadend\model\Node;

/**
 * Base class for multiplexes
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
 * @property boolean $enabled
 * @property string $name
 * @property int $epg
 * @property int $scan_state
 * @property string $charset
 * @property int $pmt_06_ac3
 */
class Multiplex extends Node
{

	const SCAN_STATE_IDLE = 0;
	const SCAN_STATE_PENDING = 1;
	const SCAN_STATE_ACTIVE = 2;

	public function __construct($name = '')
	{
		$this->name = $name;
	}


	protected function getDefaultProperties()
	{
		return array(
			'enabled' => true,
			'name' => 'New mux',
			'epg' => 1,
			'scan_state' => self::SCAN_STATE_IDLE,
			'charset' => '',
			'pmt_06_ac3' => 0,
		);
	}
}
