<?php

namespace LilleBio\Model;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use LilleBio\Model\Model;
use LilleBio\Model\Restaurant;

class Reservation extends Model
{

	private function tablePourReservation($numRest, $timestamp)
	{
		$sql = "SELECT idTable
				FROM Tables
				WHERE idTable
				NOT IN(
					SELECT idTable
					FROM Reservation
					WHERE idTable IN(
						SELECT idTable
						FROM Tables
						WHERE numRest = ".$numRest.")
					AND dateResa = ".$timestamp.")
					AND numRest = ".$numRest;

		$noTableDispo = $this->getDb()->fetchAll($sql);
		$resa['noTableDispo'] = array();
		foreach ($noTableDispo as $table) {
			$resa['noTableDispo'][] = $table['idTable'];
		}
		$tablePourResa = reset($resa['noTableDispo']);

		return $tablePourResa;
	}

	public function reserver($request)
	{
		// Initialisation des données
		$timestampDate = $request->request->get('date');
		$numRest = $request->request->get('numRest');
		$timestamp = $request->request->get('date');
		$heure = $request->request->get('heure');
		$nbrPersResa = $request->request->get('nbPers');
		$nomClient = $request->request->get('nom');
		$prenomClient = $request->request->get('prenom');
		$emailClient = $request->request->get('email');
		$telClient = $request->request->get('tel');
		$rueClient = $request->request->get('rue');
		$cpClient = $request->request->get('cp');
		$villeClient = $request->request->get('ville');
		$commResa = "";

		$explodeHeure = explode(":", $request->request->get('heure'));

		// Récupération timestamp avec date et heure
		$h = $explodeHeure[0];
		$min = $explodeHeure[1];
		$sec = $explodeHeure[2];
		$dateFromTimestamp = date("d/m/Y", $timestampDate);
		$explodeDate = explode("/", $dateFromTimestamp);
		$j = $explodeDate[0];
		$mois = $explodeDate[1];
		$a = $explodeDate[2];
		$newTimestamp = mktime($h,$min,$sec,$mois,$j,$a);
		$newDate = date("d/m/Y à H:i:s", $newTimestamp);

		// Compter nombre réservation, dans ce restaurant, à cette date là
		$sql = "SELECT count(*) nbResa
				FROM Reservation
				WHERE idTable
				IN(SELECT idTable
				FROM Tables
				WHERE numRest = ".$numRest.")
				AND dateResa = ".$newTimestamp;
		$countResa = $this->getDb()->fetchAssoc($sql);

		// Compter nombre de tables dans le restau
		$numRest = $request->request->get('numRest');
		$sql = "SELECT count(*) nbTable
				FROM Tables
				WHERE numRest = :numRest";
		$countTable = $this->getDb()->prepare($sql);
		$countTable->bindValue('numRest', $numRest);
		$countTable->execute();
		$countTable = $countTable->fetch();

		$resa = array(
			'timestamp' => $request->request->get('date'),
			'heure' => $request->request->get('heure'),
			'nbPers' => $request->request->get('nbPers'),
			'nom' => $request->request->get('nom'),
			'prenom' => $request->request->get('prenom'),
			'email' => $request->request->get('email'),
			'tel' => $request->request->get('tel'),
			'adresse' => array(
							'rue' => $request->request->get('rue'),
							'cp' => $request->request->get('cp'),
							'ville' => $request->request->get('ville')),
			'explodeHeure' => $explodeHeure,
			'dateFromTimestamp' => $dateFromTimestamp,
			'newTimestamp' => $newTimestamp,
			'newDate' => $newDate,
			'countResa' => $countResa,
			'countTable' => $countTable,
			'numRest' => $request->request->get('numRest'),
			);

		// Si il y à autant de réservation que de table, on ne peut pas réserver.
		if ($countResa['nbResa'] >= $countTable['nbTable']) {
			$resaPossible = false;
			$resa['resaPossible'] = $resaPossible;
			return false;
		} // Sinon, on peut réserver
		else {
			$resaPossible = true;
			$resa['resaPossible'] = $resaPossible;

			// Avant d'enregistrer la résa, le client existe-t-il déjà ?
			$sql = "SELECT numClient, emailClient FROM Client WHERE emailClient = :emailClient";
			$clientExiste = $this->getDb()->prepare($sql);
			$clientExiste->bindValue('emailClient', $resa['email']);
			$clientExiste->execute();
			$client = $clientExiste->fetch();

			// Si le client existe déjà en base de données
			if ($client['emailClient'] == $resa['email']) {
				$resa['clientExiste'] = true;
				$resa['client'] = $client;
				// On détermine la table du restaurant sur laquelle on peut réserver
				$tablePourResa = $this->tablePourReservation($numRest, $newTimestamp);

				////////////////////////////////////////
				// Insertion de la réservation en bdd //
				////////////////////////////////////////

				// Préparation de la requète
				$sql = "INSERT INTO Reservation(dateResa, nbrPersResa, commResa, idTable, numClient) VALUES(:dateResa, :nbrPersResa, :commResa, :idTable, :numClient)";
				$req = $this->getDb()->prepare($sql);
				// Affectation des valeurs a insérer
				$req->bindValue('dateResa', $newTimestamp);
				$req->bindValue('nbrPersResa', $nbrPersResa);
				$req->bindValue('commResa', $commResa);
				$req->bindValue('idTable', $tablePourResa);
				$req->bindValue('numClient', $client['numClient']);
				// éxecution de la requète avec test
				if ($req->execute()) {
					// On récupère le numéro de la réservation qu'on vient d'ajouter
					$sql = "SELECT LAST_INSERT_ID() numResa FROM Reservation";
					$lastResa = $this->getDb()->fetchAssoc($sql);
					$nouvelleResa['numResa'] = $lastResa['numResa'];
					$nouvelleResa['client'] = $client;
					return $lastResa['numResa'];
				}
				$resa['tablePourResa'] = $tablePourResa;
			}// Si le client n'existe pas encore en base de données
			else {
				$resa['clientExiste'] = false;
				$nouveauClient = array(
					'nom' => $request->request->get('nom'),
					'prenom' => $request->request->get('prenom'),
					'email' => $request->request->get('email'),
					'tel' => $request->request->get('tel'),
					'adresse' => array(
						'rue' => $request->request->get('rue'),
						'cp' => $request->request->get('cp'),
						'ville' => $request->request->get('ville')));
				// Insertion du nouveau client en base de données
				$sql = "INSERT INTO Client(nomClient, prenomClient, rueClient, villeClient, cpClient, telClient, emailClient) VALUES(:nomClient, :prenomClient, :rueClient, :villeClient, :cpClient, :telClient, :emailClient)";

				$req = $this->getDb()->prepare($sql);

				$req->bindValue('nomClient', $nomClient);
				$req->bindValue('prenomClient', $prenomClient);
				$req->bindValue('rueClient', $rueClient);
				$req->bindValue('villeClient', $villeClient);
				$req->bindValue('cpClient', $cpClient);
				$req->bindValue('telClient', $telClient);
				$req->bindValue('emailClient', $emailClient);

				// Si on a réussi a enregistrer le nouveau client
				if ($req->execute()) {
					// On détermine la table du restaurant sur laquelle on peut réserver
					$tablePourResa = $this->tablePourReservation($numRest, $newTimestamp);
					$resa['tablePourResa'] = $tablePourResa;
					// On récupère le numéro du client qu'on vient d'ajouter
					$sql = "SELECT MAX(numClient) numClient FROM Client";
					$lastClient = $this->getDb()->fetchAssoc($sql);
					$nouveauClient['numéro'] = $lastClient['numClient'];
					// Tous les éléments de la nouvelle réservation a enregistrer en bdd
					$nouvelleResa = array(
						'dateResa' => $newTimestamp,
						'nbrPersResa' => $nbrPersResa,
						'commResa' => '',
						'idTable' => $tablePourResa,
						'numClient' => $lastClient['numClient']);
					////////////////////////////////////////
					// Insertion de la réservation en bdd //
					////////////////////////////////////////

					// Préparation de la requète
					$sql = "INSERT INTO Reservation(dateResa, nbrPersResa, commResa, idTable, numClient) VALUES(:dateResa, :nbrPersResa, :commResa, :idTable, :numClient)";
					$req = $this->getDb()->prepare($sql);
					// Affectation des valeurs a insérer
					$req->bindValue('dateResa', $newTimestamp);
					$req->bindValue('nbrPersResa', $nbrPersResa);
					$req->bindValue('commResa', $commResa);
					$req->bindValue('idTable', $tablePourResa);
					$req->bindValue('numClient', $lastClient['numClient']);
					// éxecution de la requète avec test
					if ($req->execute()) {
						// On récupère le numéro de la réservation qu'on vient d'ajouter
						$sql = "SELECT LAST_INSERT_ID() numResa FROM Reservation";
						$lastResa = $this->getDb()->fetchAssoc($sql);
						$nouvelleResa['numResa'] = $lastResa['numResa'];
						$nouvelleResa['client'] = $nouveauClient;
						return $lastResa['numResa'];
					}
					$resa['nouvelleResa'] = $nouvelleResa;
				}
			}

		}


		return $resa;

	}// end function reservation()

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

