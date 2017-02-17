<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

namespace LilleBio\Model;

use LilleBio\Model\Model;

class Restaurateur extends Model
{

	public function uploadImage($request)
	{
		$image = $request->files->get('image');

		$restau = $request->request->get('nomRest');
		$restau = str_replace(" ", "", $restau);

		$upload_dir = "/var/www/html/LilleBio/web/img/restaurant";
		if ($image->move($upload_dir, $restau.".png")) {

			$sql = "INSERT INTO Image(nomImg, urlImg) VALUES(:nomImg, :urlImg)";
			$upload = $this->getDb()->prepare($sql);
			$upload->bindValue('nomImg', $restau);
			$upload->bindValue('urlImg', "/img/restaurant/".$restau.".png");
			if ($upload->execute()) {
				$result = true;
			} else {
				$result = "Erreur de l'insertion en bdd";
			}
		} else {
			$result = "Erreur d'upload de fichier";
		}

		return $result;

	}

	public function enregistrerRestau($request)
	{
		$nomRest = $request->request->get('nomRest');
		$rueRest = $request->request->get('rueRest');
		$cpRest = $request->request->get('cpRest');
		$villeRest = htmlspecialchars($request->request->get('villeRest'));
		$telRest = $request->request->get('telRest');
		$emailRest = $request->request->get('emailRest');
		$etoile = $request->request->get('etoile');
		$nbTable = $request->request->get('nbTable');
		$cat = $request->request->get('cat');
		$formuleRepas = $request->request->get('formuleRepas');
		$password = $request->request->get('password');
		$password = password_hash($password, PASSWORD_BCRYPT);

		if (true === $this->uploadImage($request)) {
			$sql = "SELECT LAST_INSERT_ID() numImg FROM Image";
			$img = $this->getDb()->fetchAssoc($sql);

			//return $restau;
			$sql = "INSERT INTO Restaurant(nomRest, rueRest, cpRest, villeRest, telRest, emailRest, etoileRest, nbreTableRest, catRest, formRepasRest, mdpRest, numImg) VALUES('".$nomRest."', '".$rueRest."', ".$cpRest.", '".$villeRest."', '".$telRest."', '".$emailRest."', ".$etoile.", ".$nbTable.", ".$cat.", '".$formuleRepas."', '".$password."', ".$img['numImg'].")";

			if ($this->getDb()->query($sql)) {
				$sql = "SELECT LAST_INSERT_ID() numRest FROM Restaurant";
				$restau = $this->getDb()->fetchAssoc($sql);
				return $restau;
			} else {
				return "Erreur lors de l'insertion du restau en BDD";
			}

		} else {

			return $this->uploadImage($request);

		}

	}


	public function connexion($request)
	{
		$idForm = $request->request-> get('identifiant');	
		$mdpForm = $request->request-> get('motdepasse');
		$id = "SELECT emailRest, mdpRest, numRest
 			   FROM Restaurant
 			   WHERE emailRest ='".$idForm."'"; 
 		$recupid = $this->getDb()->fetchAssoc($id);

 		if ($idForm == $recupid['emailRest']){
			if (password_verify($mdpForm, $recupid['mdpRest'])){
				return $recupid['numRest'];
				} else {
				echo "! Vous n'avez pas entré de mot de passe, ou nous ne reconnaisson pas ce dernier !";
				}
		}else{
			echo "Nous ne reconnaissons pas votre email. Etes-vous inscrit?";
		}
	}


}

