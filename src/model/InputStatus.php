<?php

namespace Jalle19\tvheadend\model;

/**
 * Represents an input status
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
 * @property string $uuid
 * @property string $input
 * @property string $stream
 * @property int $subs
 * @property int $weight
 * @property int $signal
 * @property int $signal_scale
 * @property int $ber
 * @property int $snr
 * @property int $snr_scale
 * @property int $unc
 * @property int $bps
 * @property int $te
 * @property int $cc
 * @property int $ec_bit
 * @property int $tc_bit
 * @property int $ec_block
 * @property int $tc_block
 */
class InputStatus extends Node
{

}
