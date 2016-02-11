<?php

namespace Jalle19\tvheadend\model;

/**
 * Represents a channel
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
 * 
 * @property boolean $enabled
 * @property string $name
 * @property integer $number
 * @property string $icon
 * @property string $icon_public_url
 * @property boolean $epgauto
 * @property array $epggrab
 * @property integer $dvr_pre_time
 * @property integer $dvr_post_time
 * @property array $services
 * @property array $tags
 * @property string $bouquet
 */
class Channel extends CreatableNode implements IStreamable
{

	protected function getDefaultProperties()
	{
		return array(
			'enabled'=>true,
			'name'=>'',
			'number'=>0,
			'icon'=>'',
			'icon_public_url'=>'',
			'epgauto'=>true,
			'epggrab'=>array(),
			'dvr_pre_time'=>0,
			'dvr_pst_time'=>0,
			'services'=>array(),
			'tags'=>array(),
			'bouquet'=>'');
	}
	
	public function getUrl($profile = null)
	{
		$url = '/stream/channel/' . $this->uuid;

		if ($profile !== null)
			$url .= '?profile=' . $profile;

		return $url;
	}

}
