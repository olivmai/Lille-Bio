<?php 
$date = $_GET('date');
$heure = $_GET('heure');
$couverts = $_GET('couverts');
$nom = $_GET('nom');
$prenom = $_GET('prenom');
$email = $_GET('email');
$telephone = $_GET('telephone');
$adresse = $_GET('adresse');
$ville = $_GET('ville');
$cp = $_GET('cp');

// Connextion bd

// If pour savoir si tous les champs sont remplis

// If si les conditions de réservation sont bonnes date,heure,couverts, tabel ?

// si ok = inserttion dans la base64_decode

// sinon message d'erreur


$insertion ="INSERT INTO client (nomClient,prenomClient,emailClient,telClient,rueClient,villeCLient,cpClient)";



if(!empty($_GET('date')){
    $date = $_GET('date');
}else{
    header('Location: http://www.votresite.com/pageprotegee.php');
  exit();
};