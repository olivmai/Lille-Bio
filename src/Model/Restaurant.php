<?php

namespace LilleBio\Model;

use LilleBio\Model\Model;

class Restaurant extends Model
{

	public function troisDerniersRestau()
	{
		$sql_derniers_restau = "SELECT * FROM restau ORDER BY numRest DESC LIMIT 3";
		$troisDerniersRestau = $this->getDb()->fetchAll($sql_derniers_restau);

		return $troisDerniersRestau;
	}
}

