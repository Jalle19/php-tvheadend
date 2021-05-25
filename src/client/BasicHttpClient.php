<?php

namespace Jalle19\tvheadend\client;

use Jalle19\tvheadend\exception;
use Laminas\Http\Client;

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
	 * @var \Laminas\Http\Client the HTTP client
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
		
		$this->_httpClient = new Client();
		$this->_httpClient->setAdapter(Client\Adapter\Curl::class);
		$this->_httpClient->setOptions([
			'keepalive' => true,
		]);
	}


	/**
	 * @inheritdoc
	 */
	public function setCredentials($username, $password)
	{
		$this->_username = $username;
		$this->_password = $password;

		// Pass along to the client
		$this->_httpClient->setAuth($username, $password);
	}


	/**
	 * @inheritdoc
	 */
	public function getResponse($request)
	{
		$httpRequest = $this->createBaseRequest($request->getPath());

		foreach ($request->getParameters() as $name=> $value)
			$httpRequest->getPost()->set($name, $value);

		/* @var $response \Laminas\Http\Response */
		$response = $this->_httpClient->dispatch($httpRequest);

		if ($response->getStatusCode() !== 200)
			throw new exception\RequestFailedException($response);

		return new Response($response->getContent());
	}

	/**
	 * Creates a basic HTTP request
	 * @param string $relativeUrl the relative API URL
	 * @return \Laminas\Http\Request
	 */
	protected function createBaseRequest($relativeUrl)
	{
		$baseUrl = $this->getBaseUrl(false);
		$request = new \Laminas\Http\Request();
		$request->setUri($baseUrl.$relativeUrl);
		$request->setMethod(\Laminas\Http\Request::METHOD_POST);
		$request->getHeaders()->addHeaders(array(
			'Content-Type'=>'application/x-www-form-urlencoded; charset=UTF-8',
			'Accept-Encoding'=>'identity')); // plain text

		$this->addDefaultParameters($request);

		return $request;
	}

	/**
	 * @inheritdoc
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
	 * @param \Laminas\Http\Request $request the request
	 */
	protected function addDefaultParameters(&$request)
	{
		$defaultParameters = array(
			'all'=>1,
			'dir'=>'ASC',
			'start'=>0,
			'limit'=>999999999);

		foreach ($defaultParameters as $name=> $value)
			$request->getPost()->set($name, $value);
	}

}
