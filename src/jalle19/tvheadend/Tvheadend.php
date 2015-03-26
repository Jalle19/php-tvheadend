<?php

namespace jalle19\tvheadend;

use jalle19\tvheadend\exception;

/**
 * Main class for interacting with tvheadend. Each object represents an 
 * instance of tvheadend.
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
class Tvheadend
{

	/**
	 * @var string the hostname
	 */
	private $_hostname;

	/**
	 * @var int the HTTP port
	 */
	private $_port;

	/**
	 * @var client\ClientInterface the client used for communication
	 */
	private $_client;

	/**
	 * Class constructor
	 * @param string $hostname the hostname
	 * @param int $port the port
	 * @param string|null $username (optional)
	 * @param string|null $password (optional)
	 */
	public function __construct($hostname, $port = 9981, $username = null, $password = null)
	{
		$this->_hostname = $hostname;
		$this->_port = $port;
		$this->_client = new client\BasicHttpClient($hostname, $port);

		// Set credentials
		if ($username !== null && $password !== null)
			$this->_client->setCredentials($username, $password);
	}

	/**
	 * Returns data about a node, such as the class name
	 * @param string $uuid
	 * @return stdClass the raw node data response
	 */
	public function getNodeData($uuid)
	{
		$request = new client\Request('/idnode/load', array(
			'uuid'=>$uuid,
			'meta'=>0));

		$response = $this->_client->getResponse($request);
		$content = json_decode($response->getContent());

		if (count($content->entries) > 0)
			return $content->entries[0];
		else
			return null;
	}

	/**
	 * Creates the specified network
	 * @param model\network\Network $network the network
	 */
	public function createNetwork($network)
	{
		$request = new client\Request('/mpegts/network/create', array(
			'class'=>$network->getClassName(),
			'conf'=>json_encode($network)));

		$this->_client->getResponse($request);
	}

	/**
	 * Returns the network with the specified name, or null if not found
	 * @param string $name the network name
	 * @return model\network\Network
	 */
	public function getNetwork($name)
	{
		// TODO: Use filtering
		$networks = $this->getNetworks();

		foreach ($networks as $network)
			if ($network->networkname === $name)
				return $network;

		return null;
	}

	/**
	 * Returns the list of networks
	 * @return model\network\Network[]
	 */
	public function getNetworks()
	{
		$response = $this->_client->getResponse(new client\Request('/mpegts/network/grid'));
		$content = json_decode($response->getContent());

		$networks = array();

		foreach ($content->entries as $entry)
		{
			// Retrieve the class for this network so we know what type it is
			$nodeData = $this->getNodeData($entry->uuid);

			// TODO: Remove once factory is finished
			try {
				$networks[] = model\network\Factory::fromRawEntry($nodeData->class, $entry);
			}
			catch (exception\NotImplementedException $e) {
				continue;
			}
		}

		return $networks;
	}

	/**
	 * Creates the specified multiplex on the specified network
	 * @param model\network\Network $network
	 * @param model\network\Multiplex $multiplex
	 */
	public function createMultiplex($network, $multiplex)
	{
		$request = new client\Request('/mpegts/network/mux_create', array(
			'uuid'=>$network->uuid,
			'conf'=>json_encode($multiplex)));

		$this->_client->getResponse($request);
	}

	/**
	 * Returns the list of channels
	 * @return model\Channel[]
	 */
	public function getChannels()
	{
		$channels = array();

		$response = $this->_client->getResponse(new client\Request('/channel/grid'));
		$rawContent = $response->getContent();

		$content = json_decode($rawContent);

		foreach ($content->entries as $entry)
			$channels[] = model\Channel::fromRawEntry($entry);

		return $channels;
	}
	
	/**
	 * @param \jalle19\tvheadend\IStreamable $streamable a streamable
	 * @return string the absolute URL to the streamable
	 */
	public function getAbsoluteUrl(model\IStreamable $streamable)
	{
		return $this->_client->getBaseUrl().$streamable->getUrl();
	}

	/**
	 * @return client\ClientInterface the client
	 */
	public function getClient()
	{
		return $this->_client;
	}

	/**
	 * 
	 * @param \jalle19\tvheadend\client\ClientInterface $client
	 */
	public function setClient(client\ClientInterface $client)
	{
		$this->_client = $client;
	}

}
