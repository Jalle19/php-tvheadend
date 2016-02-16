<?php

namespace Jalle19\tvheadend\model;

/**
 * Represents a service
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
 * @property string $uuid
 * @property string $network
 * @property string $multiplex
 * @property string $multiplex_uuid
 * @property int    $sid
 * @property int    $lcn
 * @property int    $lcn_minor
 * @property int    $lcn2
 * @property int    $srcid
 * @property string $svcname
 * @property string $provider
 * @property int    $dvb_servicetype
 * @property bool   $dvb_ignore_it
 * @property int    $prefcapid
 * @property int    $prefcapid_lock
 * @property int    $force_caid
 * @property int    $created
 * @property int    $last_seen
 * @property bool   $enabled
 * @property int    $auto
 * @property array  $channel
 * @property int    $priority
 * @property bool   $encrypted
 * @property string $caid
 */
class Service extends Node
{

}
