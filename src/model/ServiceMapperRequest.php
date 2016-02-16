<?php

namespace Jalle19\tvheadend\model;

/**
 * Represents a request to the service mapper
 *
 * Copyright (C) 2016 Sam Stenvall
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
 * @property array $services
 * @property bool  $encrypted
 * @property bool  $merge_same_name
 * @property bool  $check_availability
 * @property bool  $type_tags
 * @property bool  $provider_tags
 * @property bool  $network_tags
 */
class ServiceMapperRequest extends Node
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->services           = [];
		$this->encrypted          = true;
		$this->merge_same_name    = false;
		$this->check_availability = false;
		$this->type_tags          = true;
		$this->provider_tags      = false;
		$this->network_tags       = false;
	}


	/**
	 * @param Service[] $services
	 */
	public function setServices($services)
	{
		$serviceUuids = [];

		foreach ($services as $service)
			$serviceUuids[] = $service->uuid;

		$this->services = $serviceUuids;
	}

}
