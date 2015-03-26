<?php

namespace jalle19\tvheadend\client;

use jalle19\tvheadend\exception;

/**
 * Basic HTTP client for communicating with tvheadend
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
class BasicHttpClient implements ClientInterface
{

	/**
	 * @var \Zend\Http\Client the HTTP client
	 */
	private $_httpClient;

	/**
	 * @var string
	 */
	private $_hostname;

	/**
	 * @var int
	 */
	private $_port;
	
	/**
	 * @var string
	 */
	private $_username;
	
	/**
	 * @var string
	 */
	private $_password;

	/**
	 * Class constructor
	 * @param string $hostname
	 * @param int $port
	 */
	public function __construct($hostname, $port)
	{
		$this->_hostname = $hostname;
		$this->_port = $port;
		$this->_httpClient = new \Zend\Http\Client();
	}

	public function setCredentials($username, $password)
	{
		$this->_username = $username;
		$this->_password = $password;
		
		// Pass along to the client
		$this->_httpClient->setAuth($username, $password);
	}

	public function getResponse($request)
	{
		$httpRequest = $this->createBaseRequest($request->getPath());

		foreach ($request->getParameters() as $name=> $value)
			$httpRequest->getPost()->set($name, $value);

		$response = $this->_httpClient->dispatch($httpRequest);

		if ($response->getStatusCode() !== 200)
			throw new exception\RequestFailedException();

		return new Response($response->getContent());
	}

	/**
	 * Creates a basic HTTP request
	 * @param string $relativeUrl the relative API URL
	 * @return \Zend\Http\Request
	 */
	protected function createBaseRequest($relativeUrl)
	{
		$baseUrl = $this->getBaseUrl(false).'/api';
		$request = new \Zend\Http\Request();
		$request->setUri($baseUrl.$relativeUrl);
		$request->setMethod(\Zend\Http\Request::METHOD_POST);
		$request->getHeaders()->addHeaders(array(
			'Content-Type'=>'application/x-www-form-urlencoded; charset=UTF-8'));

		$this->addDefaultParameters($request);

		return $request;
	}

	/**
	 * @see ClientInterface
	 */
	public function getBaseUrl($includeCredentials = true)
	{
		$credentials = '';
		
		if ($includeCredentials)
			$credentials = $this->_username.':'.$this->_password.'@';
		
		return 'http://'.$credentials.$this->_hostname.':'.$this->_port;
	}

	/**
	 * Adds default parameters to the request, such as sorting
	 * @param Request $request the request
	 */
	protected function addDefaultParameters(&$request)
	{
		$defaultParameters = array('dir'=>'ASC');

		foreach ($defaultParameters as $name=> $value)
			$request->getPost()->set($name, $value);
	}

	/**
	 * Modifies the path to ensure it has a beginning slash and no trailing slash
	 * @param stirng $path the path
	 * @return string
	 */
	private function normalizePath($path)
	{
		return '/'.trim($path, '/');
	}

}
