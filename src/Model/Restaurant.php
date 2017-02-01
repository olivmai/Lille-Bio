<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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

	public function exemple($request)
	{
		$champ1 = $request->request->get('champ_01');
		$champ2 = $request->request->get('champ_02');
		$champ3 = $request->request->get('champ_03');
		$champ4 = $request->request->get('champ_04');

		$exemple = array(
			'champ1' => $champ1,
			'champ2' => $champ2,
			'champ3' => $champ3,
			'champ4' => $champ4);

		return $exemple;
	}
}

