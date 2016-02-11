<?php

namespace Jalle19\tvheadend\model;

use Jalle19\tvheadend\model\comet\SubscriptionNotification;

/**
 * Represents a subscription status
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
 * @author  Sam Stenvall <neggelandia@gmail.com>
 * @package Jalle19\tvheadend\model
 *
 * @property int    $id
 * @property int    $start
 * @property int    $errors
 * @property string $state
 * @property string $hostname
 * @property string $username
 * @property string $title
 * @property string $channel
 * @property string $service
 */
class SubscriptionStatus extends Node
{

	const STATE_IDLE    = 'idle';
	const STATE_TESTING = 'testing';
	const STATE_RUNNING = 'running';
	const STATE_BAD     = 'bad';

	const TYPE_STREAMING = 'streaming';
	const TYPE_RECORDING = 'dvr';
	const TYPE_EPGGRAB   = 'epggrab';


	/**
	 * @return string the subscription type
	 */
	public function getType()
	{
		if (strpos($this->title, 'DVR: ') !== false)
			return self::TYPE_RECORDING;
		else if (strpos($this->title, 'epggrab') !== false)
			return self::TYPE_EPGGRAB;

		return self::TYPE_STREAMING;
	}


	/**
	 * Augments this instance with data from the notification
	 *
	 * @param SubscriptionNotification $notification
	 */
	public function augment(SubscriptionNotification $notification)
	{
		$this->in  = $notification->in;
		$this->out = $notification->out;
	}

}
