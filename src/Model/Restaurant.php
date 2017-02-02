<?php

namespace LilleBio\Model;

use LilleBio\Model\Model;

class Restaurant extends Model
{

	public function troisDerniersRestau()
	{
		$sql_derniers_restau =
			"SELECT i.nomImg nom_image, i.urlImg url_img, r.numRest num_restau, r.nomRest nom_restau, r.catRest cat_restau, r.etoileRest etoiles, r.villeRest ville, c.nomCat
			FROM Restaurant r
			LEFT JOIN Image i
			ON i.numImg = r.numImg
			LEFT JOIN Categorie c
			ON c.numCat = r.catRest
			ORDER BY r.numRest DESC
			LIMIT 0,3";
		$troisDerniers = $this->getDb()->fetchAll($sql_derniers_restau);

		return $troisDerniers;
	}

	public function reservation()
	{
		
	}


}

