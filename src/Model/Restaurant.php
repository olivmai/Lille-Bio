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

	public function RestaurantparCategorie($categorie)
	{

		$sql_tousrestau_categorie =
			"SELECT i.nomImg nom_image, i.urlImg url_img, i.numImg, c.numCat, c.nomCat, c.numImg, r.numRest num_restau, r.catRest, r.numImg, r.nomRest nom_restau, r.etoileRest etoiles, r.villeRest ville
			FROM Restaurant r
			LEFT JOIN Image i
			ON i.numImg = r.numImg
			LEFT JOIN Categorie c
			ON c.numCat = r.catRest
			WHERE r.catRest = ".$categorie;
		$listerestaucategorie = $this->getDb()->fetchAll($sql_tousrestau_categorie);

		return $listerestaucategorie;

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

	public function getRestau($id)
	{
		$sql_restau = "SELECT i.nomImg nom_image, i.urlImg url_img, r.numRest num_restau, r.nomRest nom_restau, r.catRest cat_restau, r.etoileRest etoiles, r.villeRest ville, r.formRepasRest formule, r.rueRest rue, r.cpRest cp, c.nomCat, r.emailRest email, r.telRest tel, r.catRest numCat, r.nbreTableRest nbTable
			FROM Restaurant r
			LEFT JOIN Image i
			ON i.numImg = r.numImg
			LEFT JOIN Categorie c
			ON c.numCat = r.catRest
			WHERE r.numRest = ".$id;

		$restaurant = $this->getDb()->fetchAssoc($sql_restau);

		//pour menu : where id restau = restau && numMenu not null && categorie = entrée, plat, dessert...

		$sql_entree = "SELECT * FROM Plat WHERE catPlat = 'entree' AND numRest = ".$id." AND numMenu IS NULL";
		$listeEntrees = $this->getDb()->fetchAll($sql_entree);

		$sql_plat = "SELECT * FROM Plat WHERE catPlat = 'plat' AND numRest = ".$id." AND numMenu IS NULL";
		$listePlats = $this->getDb()->fetchAll($sql_plat);

		$sql_dessert = "SELECT * FROM Plat WHERE catPlat = 'dessert' AND numRest = ".$id." AND numMenu IS NULL";
		$listeDesserts = $this->getDb()->fetchAll($sql_dessert);

		$sql_menu_entree = "SELECT * FROM Plat WHERE catPlat = 'entree' AND numRest = ".$id." AND numMenu IS NOT NULL";
		$listeMenuEntrees = $this->getDb()->fetchAll($sql_menu_entree);

		$sql_menu_plat = "SELECT * FROM Plat WHERE catPlat = 'plat' AND numRest = ".$id." AND numMenu IS NOT NULL";
		$listeMenuPlats = $this->getDb()->fetchAll($sql_menu_plat);

		$sql_menu_dessert = "SELECT * FROM Plat WHERE catPlat = 'dessert' AND numRest = ".$id." AND numMenu IS NOT NULL";
		$listeMenuDesserts = $this->getDb()->fetchAll($sql_menu_dessert);

		$menu = array(
			'listeEntrees' => $listeMenuEntrees,
			'listePlats' => $listeMenuPlats,
			'listeDesserts' => $listeMenuDesserts
			);

		$carte = array(
			'listeEntrees' => $listeEntrees,
			'listePlats' => $listePlats,
			'listeDesserts' => $listeDesserts
			);

		$pageRestau = array(
			'menu' => $menu,
			'carte' => $carte,
			'restaurant' => $restaurant,
			);

		return $pageRestau;
	}

	public function categorieImages(){

		$sql_catImages = "SELECT C.numCat numeroCat, C.nomCat nomCat, C.numImg numeroImg, I.numImg numeroImg, I.nomImg nomImg, I.urlImg URLImg
		FROM Categorie C
		LEFT JOIN Image I
		ON C.numImg = I.numImg
		WHERE numCat
		IN (SELECT DISTINCT catRest from Restaurant)";

		$listeCategorieImg = $this->getDb()->fetchAll($sql_catImages);

		return $listeCategorieImg;

	}

	public function tousLesRestau()
	{
		$sql =
			"SELECT i.nomImg nom_image, i.urlImg url_img, r.numRest num_restau, r.nomRest nom_restau, r.catRest cat_restau, r.etoileRest etoiles, r.villeRest ville, c.nomCat
			FROM Restaurant r
			LEFT JOIN Image i
			ON i.numImg = r.numImg
			LEFT JOIN Categorie c
			ON c.numCat = r.catRest
			ORDER BY r.nomRest";

		$listeRestau = $this->getDb()->fetchAll($sql);

		return $listeRestau;
	}


}

