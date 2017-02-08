<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

namespace LilleBio\Model;

use LilleBio\Model\Model;

class Recherche extends Model
{
	public function trenteProchainsJours()
	{
	    $today = new \DateTime("now",new \DateTimezone("Europe/Paris"));
	    $today2 = new \DateTime("now",new \DateTimezone("Europe/Paris"));
	    $plusTrente = new \DateInterval('P30D');
	    $newDate = $today2->add($plusTrente);

	    $debut_date = mktime(0, 0, 0, $today->format('m'), $today->format('d'), $today->format('Y'));
	    $fin_date = mktime(0, 0, 0, $newDate->format('m'), $newDate->format('d'), $newDate->format('Y'));

	    $trenteProchainsJours = array();

	    for($i = $debut_date; $i <= $fin_date; $i+=86400)
	    {
	        $jourEn = date("l, F d Y.",$i);
	        $jour = $this->frenchDate($jourEn);
	        $numJour = date("d", $i);
	        $annee = date("Y", $i);

	        $trenteProchainsJours[] = array('jour' => $jour['nomJour']." ".$numJour." ".$jour['nomMois']." ".$annee, 'timestamp' => $i);
	    }

	    return $trenteProchainsJours;

	}

	public function listeCategories()
	{
		$sql = "SELECT *
            FROM Categorie
            WHERE numCat IN(SELECT DISTINCT catRest FROM Restaurant)";

	    $listeCategories = $this->getDb()->fetchAll($sql);

	    return $listeCategories;
	}

	public function rechercheRestau($request)
	{
		/////////////////////////////////////////////////////
	    /////////////  Initialisation des données ///////////
	    /////////////////////////////////////////////////////

		$timestamp = $request->request->get('date');
	    $nbPers = $request->request->get('nbrPers');
	    $numCat = $request->request->get('cat');

	    $explodeDate = explode(" ", date("H i s d m Y", $timestamp));

	    $j = $explodeDate[3];
	    $m = $explodeDate[4];
	    $a = $explodeDate[5];

	    $dates = array(
	        'midi' => mktime(12,0,0,$m,$j,$a),
	        'treize' => mktime(13,0,0,$m,$j,$a),
	        'dixneuf' => mktime(19,0,0,$m,$j,$a),
	        'vingt' => mktime(20,0,0,$m,$j,$a)
	        );

	    /////////////////////////////////////////////////////
	    //  Récupération de la liste des restaurants ////////
	    // ayant des reservations pour la date sélectionnée /
	    /////////////////////////////////////////////////////

		$reservations = array();

		foreach ($dates as $key => $value) {
		    $sql = "select ro.numRest
		        from Reservation re
		        left join Tables t
		        on re.idTable = t.idTable
		        left join Restaurant ro
		        on t.numRest = ro.numRest
		        where re.dateResa = ".$value;
		    $getResa = $this->getDb()->fetchAll($sql);
		    foreach ($getResa as $resa) {
		        $reservations[] = $resa;
		    }
		}

		//////////////////////////////////////////////////////////
	    // Pour chaque restau, récupération du nombre de tables //
	    // dans un tableau : 'restau' => 'nombre de table' ///////
	    //////////////////////////////////////////////////////////

		$newTab = array();
		foreach ($reservations as $resa) {
		    $newTab[] = $resa['numRest'];
		}
		/*[1]*/$result['newTab'] = $newTab;
		$nbTable = array();
		foreach ($reservations as $resa) {
		    $sql = "SELECT COUNT(*) count FROM Tables WHERE numRest = ".$resa['numRest'];
		    $countResult = $this->getDb()->fetchArray($sql);
		    $nbTable[$resa['numRest']] = $countResult[0];
		}

		//////////////////////////////////////////////////////////////
	    // Calcul pour savoir si 'nbReservation = nbTable', //////////
	    // on garde les restau complet dans un nouveau tableau ///////
	    //////////////////////////////////////////////////////////////

		$restauComplet = array();
		foreach ($nbTable as $numRest => $nbTable) {
		    $sql = "SELECT COUNT(*) FROM Reservation WHERE idTable IN(SELECT idTable FROM Tables WHERE numRest = ".$numRest.")";
		    $count = $this->getDb()->fetchArray($sql);
	        if ($count[0] == $nbTable*4) {
	            $restauComplet[] = $numRest;
	        }
		}

		////////////////////////////////////////////////////////////////
		// Finalement, requete pour récupérer la liste des restaus /////
		// qui NE SONT PAS dans le tableau des restaus complets ////////
		////////////////////////////////////////////////////////////////

		if ("null" == $numCat) {
		    $sql = "SELECT i.nomImg nom_image, i.urlImg url_img, r.numRest num_restau, r.nomRest nom_restau, r.catRest cat_restau, r.etoileRest etoiles, r.villeRest ville, r.formRepasRest formule, r.rueRest rue, r.cpRest cp, c.nomCat
					FROM Restaurant r
					LEFT JOIN Image i
					ON i.numImg = r.numImg
					LEFT JOIN Categorie c
					ON c.numCat = r.catRest
					WHERE r.numRest NOT IN( '" . implode($restauComplet, "', '") . "' )";
		} else {
		    $sql = "SELECT i.nomImg nom_image, i.urlImg url_img, r.numRest num_restau, r.nomRest nom_restau, r.catRest cat_restau, r.etoileRest etoiles, r.villeRest ville, r.formRepasRest formule, r.rueRest rue, r.cpRest cp, c.nomCat
			FROM Restaurant r
			LEFT JOIN Image i
			ON i.numImg = r.numImg
			LEFT JOIN Categorie c
			ON c.numCat = r.catRest
			WHERE r.numRest NOT IN( '" . implode($restauComplet, "', '") . "' )
			AND catRest = ".$numCat;
		}

		$restauDispo = $this->getDb()->fetchAll($sql);

		return $restauDispo;

	}

	private function frenchDate($date)
	{
	    $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
	    $french_days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
	    $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'Décember');
	    $french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
	    $newMonth = str_replace($english_months, $french_months, $date);
	    $newDay = str_replace($english_days, $french_days, $date);

	    $explodeMonth = explode(' ', $newMonth);
	    $newMonth = $explodeMonth[1];

	    $explodeDay = explode(' ', $newDay);
	    $newDay = $explodeDay[0];

	    $jour = array(
	        'nomJour' => $newDay,
	        'nomMois' => $newMonth
	        );

	    return $jour;
	}
}

