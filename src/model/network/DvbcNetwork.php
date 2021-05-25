<?php


namespace Jalle19\tvheadend\model\network;


class DvbcNetwork extends DvbNetwork
{
	public function getClassName()
	{
		return Network::CLASS_DVBC_NETWORK;
	}
}
