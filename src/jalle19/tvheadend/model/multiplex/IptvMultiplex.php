<?php

namespace jalle19\tvheadend\model\multiplex;

/**
 * Represents an IPTV multiplex
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
 * @property string $iptv_url
 * @property string $iptv_interface
 * @property boolean $iptv_atsc
 * @property string $iptv_muxname
 * @property string $iptv_sname
 * @property int $priority
 * @property int $spriority
 * @property boolean $iptv_respawn
 * @property string $iptv_env
 */
class IptvMultiplex extends Multiplex
{

	protected function getDefaultProperties()
	{
		return array_merge(parent::getDefaultProperties(), array(
			'iptv_url'=>'',
			'iptv_interface'=>'',
			'iptv_atsc'=>false,
			'iptv_muxname'=>'',
			'iptv_sname'=>'',
			'priority'=>0,
			'spriority'=>0,
			'iptv_respawn'=>false,
			'iptv_env'=>''));
	}

}
