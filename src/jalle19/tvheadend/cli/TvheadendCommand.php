<?php

namespace jalle19\tvheadend\cli;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

/**
 * Base class for commands that utilize the tvheadend API
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
class TvheadendCommand
{

	/**
	 * @var \Commando\Command the command arguments helper
	 */
	protected $command;

	/**
	 * @var Logger the logger instance 
	 */
	protected $logger;

	/**
	 * Base constructor. Subclasses should call this first so the arguments 
	 * required for tvheadend communication can be set
	 * @param string $prefix prefix for the basic options, e.g. "source" 
	 * changes the option "tvheadend-hostname" to "source-tvheadend-hostname"
	 */
	public function __construct($prefix = '')
	{
		// Configure the logger
		$dateFormat = 'Y-m-d H:i:s';
		$output = "[%datetime%] %level_name%: %message%\n";

		$handler = new StreamHandler('php://stdout', Logger::DEBUG);
		$handler->setFormatter(new LineFormatter($output, $dateFormat));

		$this->logger = new Logger('logger');
		$this->logger->pushHandler($handler);

		$command = new \Commando\Command();
		
		if (!empty($prefix))
			$prefix .= '-';

		// Required arguments
		$command->option($prefix.'tvheadend-hostname')
				->require()
				->describeAs('The hostname where tvheadend is running');

		// Optional arguments
		$command->option($prefix.'tvheadend-http-port')
				->default(9981)
				->describeAs('The tvheadend HTTP port');

		$command->option($prefix.'tvheadend-username')
				->describeAs('The tvheadend username');

		$command->option($prefix.'tvheadend-password')
				->describeAs('The tvheadend password');

		$this->command = $command;
	}

	/**
	 * Prompts the user for a response
	 * @param string $question the question to ask
	 * @param string $choices the available choices
	 * @param string $default the default choice when no input is entered
	 * @return string the choice
	 */
	protected function askQuestion($question, $choices, $default)
	{
		$response = '';

		// Ask until we get a valid response
		do
		{
			echo $question.' '.$choices.': ';
			
			$handle = fopen("php://stdin", "r");
			$line = fgets($handle);

			$response = trim($line);

			// Use default when no response is given
			if (empty($response))
				$response = $default;
		}
		while (!in_array($response, explode('/', $choices)));

		return $response;
	}

}
