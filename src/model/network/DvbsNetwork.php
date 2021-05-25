<?php


namespace Jalle19\tvheadend\model\network;


class DvbsNetwork extends DvbNetwork
{
	public function getClassName()
	{
		return Network::CLASS_DVBS_NETWORK;
	}
}
