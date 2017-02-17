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


	public function infosrestaurateur($resquest)
	{
		 
            $Nom = $request->request->get('Nom');
            $Adresse = $request->request->get('Adresse');
            $CodePostal = $request->request->get('CodePostal');
            $Ville = $request->request->get ('Ville');
            $Telephone = $request->request->get('Telephone');
            $Email = $request->request->get('Email');
            $Nombre_tables = $request->request->get('Nombre_tables');
            $Nombre_couverts = $request->request->get('Nombre_couverts');
            $Categorie = $request->request->get('Categorie');
            $Nombre_etoiles = $request->request->get('Nombre_etoiles');
            $Formule = $request->request->get('Formule');
        
                if($Nom && $Adresse && $CodePostal && $Ville && $Telephone && $Nombre_tables && $Nombre_couverts && $Categorie && $Nombre_etoiles && $Formule){
                    
                } else {
                     echo "Veuillez remplir tous les champs";
                     exit();
                };
				
				$sql = "INSERT INTO restaurant (nomRest,rueRest, cpRest,villeRest,telRest,emailRest,etoileRest,nbreTableRest,catRest,formRepasRest)
                VALUES  (':Nom',':Adresse',:CodePostal,':Ville',':Telephone',':Email',:Nombre_etoiles,:Nombre_tables,:Categorie,':Formule')";
				$insertionMenu1s->execute()){
					return true;
				};

	}

	public function infosEntrees ($request)
	{
			$numRest = $request->request->get('numRest');

			$Entree1 = $request->request->get('Entree1');
            $catEntree1 = $request->request->get('categorie1');
            $PrixEntree1 = $request->request->get('PrixEntree1');
			
            
            $Entree2 = $request->request->get('Entree2');
            $catEntree2 = $request->request->get('categorie2');
            $PrixEntree2 = $request->request->get ('PrixEntree2');
            
            $Entree3 = $request->request->get('Entree3');
            $catEntree3 = $request->request->get('categorie3');
            $PrixEntree3 = $request->request->get('PrixEntree3');
            
            $Entree4 = $request->request->get('Entree4');
            $catEntree4 = $request->request->get('categorie4');
            $PrixEntree4 = $request->request->get('PrixEntree4');
            
            $Entree5 = $request->request->get('Entree5');
            $catEntree5 = $request->request->get('categorie5');
            $PrixEntree5 = $request->request->get('PrixEntree5');

			$tableau = array(
					'Entree1'=>$Entree1,
					'categorie1' => $catEntree1,
					'PrixEntree1' => $PrixEntree1,
					
					
					'Entree2'=> $Entree2,
					'categorie2'=>$catEntree2,
					'PrixEntree2'=>$PrixEntree2,
					
					'Entree3'=>$Entree3,
					'categorie3'=>$catEntree3,
					'PrixEntree3'=>$PrixEntree3,
					
					'Entree4'=>$Entree4,
					'categorie4'=>$catEntree4,
					'PrixEntree4'=>$PrixEntree4,
					
					'Entree5'=>$Entree5,
					'categorie5'=>$catEntree5,
					'PrixEntree5'=>$PrixEntree5,

			);

			for ($i = 1; $i < 6 ; $i++){
				
					$Entree = $tableau['Entree'.$i];
					$PrixEntree = $tableau['PrixEntree'.$i];
					$catEntree = $tableau['categorie'.$i];

				
				$sql = "INSERT INTO Plat (nomPlat,prixPlat,catPlat,numRest)
						VALUES  (:Entree,:PrixEntree,:catEntree,:numRest)";

					$insertionMenu1->execute();
				
			}

			return true;
	}

	public function infosPlats ($request)
	{
			$numRest = $request->request->get('numRest');

			$Plat1 = $request->request->get('Plat1');
            $catPlat1 = $request->request->get('catPlat1');
            $PrixPlat1 = $request->request->get('PrixPlat1');
            
            $Plat2 = $request->request->get('Plat2');
            $catPlat2 = $request->request->get('catPlat2');
            $PrixPlat2 = $request->request->get('PrixPlat2');
            
            $Plat3 = $request->request->get('Plat3');
            $catPlat3 = $request->request->get('catPlat3');
            $PrixPlat3 = $request->request->get('PrixPlat3');
            
            $Plat4 = $request->request->get('Plat4');
            $catPlat4 = $request->request->get('catPlat4');
            $PrixPlat4 = $request->request->get('PrixPlat4');
            
            $Plat5 = $request->request->get('Plat5');
            $catPlat5 = $request->request->get('catPlat5');
            $PrixPlat5 = $request->request->get('PrixPlat5');

			$tableau = array(

					'Plat1' => $Plat1,
					'catPlat1' => $catPlat1,
					'PrixPlat1'=> $PrixPlat1,

					'Plat2' => $Plat2,
					'catPlat2' => $catPlat2,
					'PrixPlat2'=> $PrixPlat2,

					'Plat3' => $Plat3,
					'catPlat3' => $catPlat3,
					'PrixPlat3'=> $PrixPlat3,

					'Plat4' => $Plat4,
					'catPlat4' => $catPlat4,
					'PrixPlat4'=> $PrixPlat4,

					'Plat5' => $Plat5,
					'catPlat5' => $catPlat5,
					'PrixPlat5'=> $PrixPlat5,
			);

			for ($i = 1; $i < 6 ; $i++){
				
					$Plat = $tableau['Plat'.$i];
					$PrixPlat = $tableau['PrixPlat'.$i];
					$catPlat = $tableau['catPlat'.$i];

				
				$sql = "INSERT INTO Plat (nomPlat,prixPlat,catPlat,numRest)
						VALUES  (:Plat,:PrixPlat,:catPlat,:numRest)";

					$insertionMenu1->execute();
				
			}

			return true;
	}

	public function infosMenu1 ($request)
	{
		$numRest = $request->request->get('numRest');

		$Dessert1 = $request->request->get('Dessert1');
        $catDessert1 = $request->request->get('catDessert1');
        $PrixDessert1 = $request->request->get('PrixDessert1');
            
        $Dessert2 = $request->request->get('Dessert2');
        $catDessert2 = $request->request->get('catDessert2');
        $PrixDessert2 = $request->request->get('PrixDessert2');
            
        $Dessert3 = $request->request->get('Dessert3');
        $catDessert3 = $request->request->get('catDessert3');
        $PrixDessert3 = $request->request->get('PrixDessert3');
            
        $Dessert4 = $request->request->get('Dessert4');
        $catDessert4 = $request->request->get('catDessert4');
        $PrixDessert4 = $request->request->get('PrixDessert4');
            
        $Dessert5 = $request->request->get('Dessert5');
        $catDessert5 = $request->request->get('catDessert5');
        $PrixDessert5 = $request->request->get('PrixDessert5');

		$tableau = array(

					'Dessert1' => $Dessert1,
					'catDessert1' => $catDessert1,
					'PrixDessert1'=> $PrixDessert1,

					'Dessert2' => $Dessert2,
					'catDessert2' => $catDessert2,
					'PrixDessert2'=> $PrixDessert2,

					'Dessert3' => $Dessert3,
					'catDessert3' => $catDessert3,
					'PrixDessert3'=> $PrixDessert3,

					'Dessert4' => $Dessert4,
					'catDessert4' => $catDessert4,
					'PrixDessert4'=> $PrixDessert4,

					'Dessert5' => $Dessert5,
					'catDessert5' => $catDessert5,
					'PrixDessert5'=> $PrixDessert5,
		);

		for ($i = 1; $i < 6 ; $i++){
				
					$Dessert = $tableau['Dessert'.$i];
					$PrixDessert = $tableau['PrixDessert'.$i];
					$catDessert = $tableau['catDessert'.$i];

				
				$sql = "INSERT INTO Plat (nomPlat,prixPlat,catPlat,numRest)
						VALUES  (:Dessert,:PrixDessert,:catDessert,:numRest)";

					$insertionMenu1->execute();
				
			}

			return true;
	}

	public function infosMenu1 ($request)
	{
		$numRest = $request->request->get('numRest');

		$NomMenu= $request->request->get('NomMenu');
        $PrixMenu = $request->request->get('PrixMenu');
        $EntreeMenu = $request->request->get('EntreeMenu');
        $catEntreeMenu = $request->request->get('catEntreeMenu');
        $PlatMenu = $request->request->get('PlatMenu');
        $catPlatMenu = $request->request->get('catPlatMenu');
        $DessertMenu = $request->request->get('DessertMenu');
        $catDessertMenu = $request->request->get('catDessertMenu');

		$tableau = array(

					'NomMenu' => $NomMenu,
					'PrixMenu' => $PrixMenu,
					'EntreeMenu' => $EntreeMenu,
					'catEntreeMenu' => $catEntreeMenu,
					'PlatMenu' => $PlatMenu,
					'catPlatMenu' => $catPlatMenu,
					'DessertMenu' => $DessertMenu,
					'catDessertMenu' => $catDessertMenu,
		);

		if($tableau['NomMenu'] && $tableau['PrixMenu']){

				$sql = "INSERT INTO menu (nomMenu,prixMenu)
                VALUES  (:NomMenu,:PrixMenu)";

					$insertionMenu1 = $this->getDb()->prepare($sql);
					$insertionMenu1 -> bindValue('NomMenu',$NomMenu);
					$insertionMenu1 -> bindValue('PrixMenu',$PrixMenu);
					$insertionMenu1-> execute();

				 $sql2 = "SELECT LAST_INSERT_ID() numMenu
                        FROM menu";

					 $numMenu = $this -> getDb() -> fetchArray ($sql2);

		if($tableau['EntreeMenu'] && $tableau['PlatMenu'] && $tableau['DessertMenu']){
               
               	$sql3 = "INSERT INTO plat (nomPlat,catPlat,numMenu,numRest)
						VALUES  (:EntreeMenu,:catEntreeMenu,:numMenu,:numRest),
								(:PlatMenu,:catPlatMenu,:numMenu,:numRest),
								(:DessertMenu, :$catDessertMenu,:numMenu,:numRest)";

					$insertionMenu1 = $this->getDb()->prepare($sql3);
					$insertionMenu1 -> bindValue('EntreeMenu',$EntreeMenu);
					$insertionMenu1 -> bindValue('catEntreeMenu',$catEntreeMenu);
					$insertionMenu1 -> bindValue('numMenu',$numMenu[0]);
					$insertionMenu1 -> bindValue('numRest',$numRest);
					$insertionMenu1 -> bindValue('PlatMenu',$PlatMenu);
					$insertionMenu1 -> bindValue('catPlatMenu',$catPlatMenu);
					$insertionMenu1 -> bindValue('numMenu',$numMenu[0]);
					$insertionMenu1 -> bindValue('numRest',$numRest);
					$insertionMenu1 -> bindValue('DessertMenu',$DessertMenu);
					$insertionMenu1 -> bindValue('catDessertMenu',$catDessertMenu);
					$insertionMenu1 -> bindValue('numMenu',$numMenu[0]);
					$insertionMenu1 -> bindValue('numRest',$numRest);
					$insertionMenu1-> execute();

						//erreur syntax ligne102
				
			}

			return true;
		}
	}


	public function connexion($request)
	{
		$idForm = $request->request-> get('identifiant');	
		$mdpForm = $request->request-> get('motdepasse');
		$id = "SELECT emailRest, mdpRest, numRest
 			   FROM restaurant
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

