<?php

namespace Jalle19\tvheadend;

use Jalle19\tvheadend\exception;
use Jalle19\tvheadend\model\comet\BoxId;
use Jalle19\tvheadend\model\comet\InputStatusNotification;
use Jalle19\tvheadend\model\comet\LogMessageNotification;
use Jalle19\tvheadend\model\comet\SubscriptionNotification;
use Jalle19\tvheadend\model\ConnectionStatus;
use Jalle19\tvheadend\model\Filter;
use Jalle19\tvheadend\model\InputStatus;
use Jalle19\tvheadend\model\IStreamable;
use Jalle19\tvheadend\model\multiplex\Multiplex;
use Jalle19\tvheadend\model\ServiceMapperRequest;

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
	 * The maximum boxid age (in seconds)
	 */
	const MAXIMUM_BOXID_AGE = 5;

	const NOTIFICATION_CLASS_INPUT_STATUS = 'input_status';
	const NOTIFICATION_CLASS_SUBSCRIPTIONS = 'subscriptions';
	const NOTIFICATION_CLASS_LOG_MESSAGE = 'logmessage';

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
	 * @var BoxId[] the list of which box IDs to use for which comet poll
	 * requests. Each key is a notification class and the value is the
	 * corresponding box ID.
	 */
	private $_boxIds = array();

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
			$this->setCredentials($username, $password);
	}


	/**
	 * Sets the credentials to use
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function setCredentials($username, $password)
	{
		$this->_client->setCredentials($username, $password);
	}

	/**
	 * Attempts to retrieve the root path on the server to test
	 * the connection
	 *
	 * @throws exception\RequestFailedException if the connection attempt fails
	 */
	public function attemptConnection()
	{
		$request = new client\Request('/');
		$this->_client->getResponse($request);
	}


	/**
	 * @return string the hostname
	 */
	public function getHostname()
	{
		return $this->_hostname;
	}


	/**
	 * @return int the port
	 */
	public function getPort()
	{
		return $this->_port;
	}


	/**
	 * Returns data about a node, such as the class name
	 * @param string $uuid
	 * @return \stdClass the raw node data response
	 */
	public function getNodeData($uuid)
	{
		$request = new client\Request('/api/idnode/load', array(
			'uuid' => $uuid,
			'meta' => 0));

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
		$request = new client\Request('/api/mpegts/network/create', array(
			'class' => $network->getClassName(),
			'conf' => json_encode($network)));

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
		$response = $this->_client->getResponse(new client\Request('/api/mpegts/network/grid'));
		$content = json_decode($response->getContent());

		$networks = array();

		foreach ($content->entries as $entry) {
			// Retrieve the class for this network so we know what type it is
			$nodeData = $this->getNodeData($entry->uuid);

			// TODO: Remove once factory is finished
			try {
				$networks[] = model\network\Factory::fromRawEntry($nodeData->class, $entry);
			} catch (exception\NotImplementedException $e) {
				continue;
			}
		}

		return $networks;
	}

	/**
	 * Creates the specified multiplex on the specified network
	 * @param model\network\Network $network
	 * @param model\multiplex\Multiplex $multiplex
	 */
	public function createMultiplex($network, $multiplex)
	{
		$request = new client\Request('/api/mpegts/network/mux_create', array(
			'uuid' => $network->uuid,
			'conf' => json_encode($multiplex)));

		$this->_client->getResponse($request);
	}

	/**
	 * @param Filter|null $filter
	 * @return array
	 * @throws exception\RequestFailedException
	 */
	public function getMultiplexes(Filter $filter = null)
	{
		$request = new client\Request('/api/mpegts/mux/grid', ['limit' => 9999]);

		if ($filter !== null) {
			$request->setFilter($filter);
		}

		$response = $this->_client->getResponse($request);
		$content = json_decode($response->getContent());

		$muxes = [];

		foreach ($content->entries as $entry) {
			$muxes[] = Multiplex::fromRawEntry($entry);
		}

		return $muxes;
	}

	/**
	 * Returns the list of channels
	 * @param model\Filter $filter (optional) filter to use
	 * @return model\Channel[]
	 */
	public function getChannels($filter = null)
	{
		$channels = array();

		// Create the request
		$request = new client\Request('/api/channel/grid');

		if ($filter)
			$request->setFilter($filter);

		// Get the response
		$response = $this->_client->getResponse($request);
		$rawContent = $response->getContent();

		$content = json_decode($rawContent);

		foreach ($content->entries as $entry)
			$channels[] = model\Channel::fromRawEntry($entry);

		return $channels;
	}

	/**
	 * Returns the list of services
	 * @param model\Filter $filter (optional) filter to use
	 * @return model\Service[]
	 */
	public function getServices($filter = null)
	{
		$services = array();

		// Create the request
		$request = new client\Request('/api/mpegts/service/grid');

		if ($filter)
			$request->setFilter($filter);

		// Get the response
		$response = $this->_client->getResponse($request);
		$rawContent = $response->getContent();

		$content = json_decode($rawContent);

		foreach ($content->entries as $entry)
			$services[] = model\Service::fromRawEntry($entry);

		return $services;
	}

	/**
	 * Retrieves the EPG events for the specified channel
	 * @param model\Channel $channel
	 * @param int $limit (optional) limit how many events are returned
	 *
	 * @return model\Event[] the events
	 */
	public function getEpgForChannel(model\Channel $channel, $limit = 0)
	{
		$params = array(
			'start' => 0,
			'channel' => $channel->uuid,
		);

		if ($limit > 0)
			$params['limit'] = $limit;

		$request = new client\Request('/api/epg/events/grid', $params);
		$response = $this->_client->getResponse($request);
		$rawContent = $response->getContent();

		$content = json_decode($rawContent);
		$events = [];

		if ($content->totalCount > 0) {
			foreach ($content->entries as $entry)
				$events[] = model\Event::fromRawEntry($entry);
		}

		return $events;
	}

	/**
	 * @return model\SubscriptionStatus[]
	 */
	public function getSubscriptionStatus()
	{
		$subscriptions = [];
		$request = new client\Request('/api/status/subscriptions');

		$response = $this->_client->getResponse($request);
		$rawContent = $response->getContent();

		$content = json_decode($rawContent);

		// If we have any subscriptions, request additional data through the comet poller
		if (count($content->entries) > 0)
			$notifications = $this->getCometNotifications(self::NOTIFICATION_CLASS_SUBSCRIPTIONS);
		else
			$notifications = array();

		foreach ($content->entries as $rawEntry) {
			$subscription = model\SubscriptionStatus::fromRawEntry($rawEntry);

			// See if there's a notification for this subscription
			foreach ($notifications as $notification)
				if ($notification->id === $subscription->id)
					$subscription->augment($notification);

			$subscriptions[] = $subscription;
		}

		return $subscriptions;
	}


	/**
	 * @return ConnectionStatus[]
	 */
	public function getConnectionStatus()
	{
		$connections = [];
		$request = new client\Request('/api/status/connections');

		$response = $this->_client->getResponse($request);
		$rawContent = $response->getContent();

		$content = json_decode($rawContent);

		foreach ($content->entries as $rawEntry)
			$connections[] = model\ConnectionStatus::fromRawEntry($rawEntry);

		return $connections;
	}


	/**
	 * @return InputStatus[]
	 */
	public function getInputStatus()
	{
		$inputs = [];
		$request = new client\Request('/api/status/inputs');

		$response = $this->_client->getResponse($request);
		$rawContent = $response->getContent();

		$content = json_decode($rawContent);

		foreach ($content->entries as $rawEntry)
			$inputs[] = model\InputStatus::fromRawEntry($rawEntry);

		return $inputs;
	}


	/**
	 * Returns the available log messages
	 * @return LogMessageNotification[] the log messages
	 */
	public function getLogMessages()
	{
		return $this->getCometNotifications(self::NOTIFICATION_CLASS_LOG_MESSAGE);
	}


	/**
	 * @param string $class the type of notifications to return
	 * @param boolean $immediate (optional) whether to long poll or return immediately
	 * @return array the comet status notifications
	 */
	public function getCometNotifications($class, $immediate = true)
	{
		$this->ensureValidBoxId($class);

		$request = new client\Request('/comet/poll', array(
			'boxid' => $this->_boxIds[$class]->getBoxId(),
			'immediate' => $immediate ? 1 : 0,
		));

		$response = $this->_client->getResponse($request);
		$content = json_decode($response->getContent());

		// Parse the messages
		$messages = array();

		foreach ($content->messages as $message) {
			$notificationClass = $message->notificationClass;

			if ($notificationClass !== $class)
				continue;

			switch ($notificationClass) {
				case self::NOTIFICATION_CLASS_INPUT_STATUS:
					$messages[] = InputStatusNotification::fromRawEntry($message);
					break;
				case self::NOTIFICATION_CLASS_SUBSCRIPTIONS:
					$messages[] = SubscriptionNotification::fromRawEntry($message);
					break;
				case self::NOTIFICATION_CLASS_LOG_MESSAGE:
					$messages[] = LogMessageNotification::fromRawEntry($message);
					break;
			}
		}

		return $messages;
	}


	/**
	 * @param ServiceMapperRequest $serviceMapperRequest
	 */
	public function mapServices(ServiceMapperRequest $serviceMapperRequest)
	{
		$request = new client\Request('/api/service/mapper/save', [
			'node' => json_encode($serviceMapperRequest),
		]);

		$this->_client->getResponse($request);
	}


	/**
	 * @param IStreamable $streamable a streamable
	 * @param string $profile (optional) which streaming profile to use. Defaults
	 * to null, meaning the server decides
	 * @return string the absolute URL to the streamable
	 */
	public function getAbsoluteUrl(IStreamable $streamable, $profile = null)
	{
		return $this->_client->getBaseUrl() . $streamable->getUrl($profile);
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
	 * @param \Jalle19\tvheadend\client\ClientInterface $client
	 */
	public function setClient(client\ClientInterface $client)
	{
		$this->_client = $client;
	}

	/**
	 * Requests and returns a new "boxid" from the comet poll API
	 * @return BoxId
	 */
	private function generateCometPollBoxId()
	{
		$request = new client\Request('/comet/poll');
		$response = $this->_client->getResponse($request);

		$content = json_decode($response->getContent());
		$boxId = $content->boxid;

		return new BoxId($boxId);
	}

	/**
	 * Ensures that the specifeid class has a valid box ID defined
	 * @param string $class
	 */
	private function ensureValidBoxId($class)
	{
		if (!array_key_exists($class, $this->_boxIds) || $this->_boxIds[$class]->getAge() > self::MAXIMUM_BOXID_AGE)
			$this->_boxIds[$class] = $this->generateCometPollBoxId();
	}

}
