<?php


namespace Jalle19\tvheadend\model\network;


class IptvAutoNetwork extends IptvNetwork
{
	public function getClassName()
	{
		return Network::CLASS_IPTV_AUTO_NETWORK;
	}
}
