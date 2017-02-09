<?php

namespace LilleBio\Model;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use LilleBio\Model\Model;
use LilleBio\Model\Restaurant;

class Reservation extends Model
{

	public function rechercheReservation($request)
	{
		/////////////////////////////////////////////////////
	    /////////////  Initialisation des données ///////////
	    /////////////////////////////////////////////////////

		$email = htmlentities($request->request->get('emailResa'));
	    $numResa = $request->request->get('numResa');

	    /////////////////////////////////////////////////////
	    ///////  Récupération de la reservation /////////////
	    /////// correspondant au numéro de réservation //////
	    /////////////////////////////////////////////////////

	    $sql = 	"SELECT re.*, cl.*
		        FROM Reservation re
		        LEFT JOIN Client cl
		        ON cl.numClient = re.numClient
		        WHERE re.numResa = ".$numResa;

		$resa = $this->getDb()->fetchAll($sql);
		$reservation = $resa[0];

		$sql = "SELECT * FROM Restaurant WHERE numRest = (SELECT numRest FROM Tables WHERE idTable = ".$reservation['idTable'].")";

		$restau = $this->getDb()->fetchAll($sql);
		$restaurant = $restau[0];

		$result = array(
			'resa' => $reservation,
			'restau' => $restaurant);

		return $result;

	}

	public function modifReservation($request)
	{
		$nbPers = $request->request->get('nbPers');
		$numResa = $request->request->get('numResa');
		$email = htmlentities($request->request->get('emailResa'));

		$sql = "UPDATE Reservation SET nbrPersResa = ".$nbPers." WHERE numResa = ".$numResa;

		$this->getDb()->query($sql);

		$sql2 = 	"SELECT re.*, cl.*
		        FROM Reservation re
		        LEFT JOIN Client cl
		        ON cl.numClient = re.numClient
		        WHERE re.numResa = ".$numResa;

		$resa = $this->getDb()->fetchAll($sql2);
		$reservation = $resa[0];

		$sql = "SELECT * FROM Restaurant WHERE numRest = (SELECT numRest FROM Tables WHERE idTable = ".$reservation['idTable'].")";

		$restau = $this->getDb()->fetchAll($sql);
		$restaurant = $restau[0];

		$result = array(
			'resa' => $reservation,
			'restau' => $restaurant);

		return $result;
	}

	public function supprReservation($id)
	{
		$sql = "DELETE FROM Reservation WHERE numResa = ".$id;

		if ($this->getDb()->query($sql)) {
			return true;
		}

		return false;
	}

}

