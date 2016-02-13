php-tvheadend
=============

This is a PHP library for communicating with and controlling tvheadend instances. So far it is quite limited, it only 
supports the features I've needed myself so far. It is built on a generic framework where each class represents a 
model in tvheadend, e.g. a network, a mux, a channel and so on. The library communicates with tvheadend using a 
HTTP client. The client can be overridden if necessary.

The library also includes a base class for creating CLI commands that perform specific tasks against tvheadend. 
To utilize it, simply include this library in your project and extend the `Jalle19\tvheadend\cli\TvheadendCommand` 
class. The base command provides a way to request switches and flags from the user, as well as asking for input.

### Installation

```
composer require jalle19/php-tvheadend
```

### Example usage (library)

```php
require_once('/path/to/vendor/autoload.php');
use Jalle19\tvheadend;

// Create a new instance
$tvheadend = new tvheadend\Tvheadend('localhost', 9981);

// Create an IPTV network
$network = new tvheadend\model\network\IptvNetwork();
$network->networkname = 'Test network';
$network->max_streams = 5;

try {
  $tvheadend->createNetwork($network);
}
catch (tvheadend\exception\RequestFailedException $e) {
  die('Failed to create network');
}

// Loop through all networks and print their respective names
foreach ($tvheadend->getNetworks as $network)
  echo $network->networkname.PHP_EOL;
```

### Example usage (command)

```php
// let's call this file command.php
require_once(__DIR__.'/vendor/autoload.php');
new Jalle19\tvheadend\cli\TvheadendCommand();
```

Output when run using `php command.php`:

```
 command.php                                                                                                                 
--help
     Show the help page for this command.

--tvheadend-hostname <argument>
     Required. The hostname where tvheadend is running

--tvheadend-http-port <argument>
     The tvheadend HTTP port

--tvheadend-password <argument>
     The tvheadend password

--tvheadend-username <argument>
     The tvheadend username
```

### License
The library is licensed under the GNU GPL verison 2
