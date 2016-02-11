<?php

namespace Jalle19\tvheadend\model;

/**
 * Interfaces for models that can be created through the API
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
abstract class CreatableNode extends Node
{

	/**
	 * @return array the default properties for this model
	 */
	abstract protected function getDefaultProperties();


	/**
	 * @inheritdoc
	 */
	public function jsonSerialize()
	{
		return array_merge($this->getDefaultProperties(), $this->_properties);
	}

}