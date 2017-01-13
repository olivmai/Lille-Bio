<?php

namespace LilleBio\Model;

use LilleBio\Model\Model;

class Dragon extends Model
{

	public function listeDragons()
	{
		$sql_liste_dragon = "SELECT * FROM dragon";
		$dragons = $this->getDb()->fetchAll($sql_liste_dragon);

		return $dragons;
	}
}

