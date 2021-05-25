<?php

namespace Jalle19\tvheadend\model\network;

use Jalle19\tvheadend\model\ClassNode;

/**
 * Base class for networks
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
 * @property string $networkname
 * @property boolean $autodiscovery
 * @property boolean $skipinitscan
 * @property boolean $sid_chnum
 * @property boolean $ignore_chnum
 * @property int $nid
 * @property boolean $idlescan
 * @property string $charset
 * @property boolean $localtime
 */
abstract class Network extends ClassNode
{

	const CLASS_IPTV = 'iptv_network';
	const CLASS_IPTV_AUTO_NETWORK = 'iptv_auto_network';
	const CLASS_DVBS_NETWORK = 'dvb_network_dvbs';
	const CLASS_DVBC_NETWORK = 'dvb_network_dvbc';

	/**
	 * Network constructor.
	 * @param string $name
	 */
	public function __construct($name = '')
	{
		$this->networkname = $name;
	}

	protected function getDefaultProperties()
	{
		return array(
			'networkname' => '',
			'autodiscovery' => true,
			'skipinitscan' => true,
			'sid_chnum' => false,
			'ignore_chnum' => false,
			'nid' => 0,
			'idlescan' => false,
			'charset' => '',
			'localtime' => false);
	}

}
