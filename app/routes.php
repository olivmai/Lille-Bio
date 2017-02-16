<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

// Home page
$app->get('/', function () use ($app) {
	// Requete qui récupère les trois derniers restau avec leurs images et catégories
	$troisDerniersRestau = $app['model.restaurant']->troisDerniersRestau();
	$trenteProchainsJours = $app['model.recherche']->trenteProchainsJours();
	$listeCategories = $app['model.recherche']->listeCategories();
	$listeCategorieImg = $app['model.restaurant']->categorieImages();
	// Appel de la vue page d'accueil
    return $app['twig']->render('index.html.twig', array('listeRestau' => $troisDerniersRestau, 'date' => $trenteProchainsJours, 'listeCat' => $listeCategories, 'listeCatImg' => $listeCategorieImg));
    
})->bind('home');

// Aide utilisateurs
$app->get('/aide-utilisateurs', function () use ($app) {
	return $app['twig']->render('aide-utilisateur.html.twig');
})->bind('aide_utilisateurs');

// Restaurant
$app->get('/restaurant/{id}', function ($id) use ($app) {
	$pageRestau = $app['model.restaurant']->getRestau($id);
	return $app['twig']->render('restaurant.html.twig', array('dataRest' => $pageRestau));
})->bind('restaurant');


// Script réservation
$app->post('/reservation', function (Request $request) use ($app) {
	$reservation=$request->get('reservation');
	var_dump($request);
})->bind('reservation');


// Toutes les catégories
$app->post('/resultats', function (Request $request) use ($app) {
	return $app['twig']->render('resultats.html.twig');
})->bind('resultats');

// Restaurant
$app->post('/exemple', function (Request $request) use ($app) {
	$exemple = $app['model.restaurant']->exemple($request);
	return var_dump($exemple);
})->bind('exemple');

// Mes reservations
$app->get('/mes-reservations', function () use ($app) {
	return $app['twig']->render('mes-reservations.html.twig');
})->bind('mes_reservations');

// Espace restaurateur
$app->get('/espace-restaurateur', function () use ($app) {
	return $app['twig']->render('espace-restaurateur.html.twig');
})->bind('espace_restau');

// Recherche restau
$app->post('/recherche-restaurant', function (Request $request) use ($app) {
	$listeRestau = $app['model.recherche']->rechercheRestau($request);
	return $app['twig']->render('liste-restaurant.html.twig', array('listeRestau' => $listeRestau));
})->bind('recherche_restau');

