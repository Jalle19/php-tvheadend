<?php

namespace Jalle19\tvheadend\client;

use Jalle19\tvheadend\exception\RequestFailedException;

/**
 * The interface for API clients
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
interface ClientInterface
{

	/**
	 * Adds the specified credentials to all HTTP requests
	 * @param string $username
	 * @param string $password
	 */
	public function setCredentials($username, $password);

	/**
	 * Executes the specified request and returns the response
	 * @param Request $request
	 * @return Response
	 * @throws RequestFailedException
	 */
	public function getResponse($request);
	
	/**
	 * Returns the base URL to the instance it is connected to, e.g. 
	 * http://localhost:9981
	 * @param boolean $includeCredentials whether to include eventual 
	 * credentials in the URL. Defaults to true.
	 * @return string the URL
	 */
	public function getBaseUrl($includeCredentials = true);
}
