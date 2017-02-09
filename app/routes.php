<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\UrlGeneratorServiceProvider;

// Home page
$app->get('/', function () use ($app) {
	// Requete qui récupère les trois derniers restau avec leurs images et catégories
	$troisDerniersRestau = $app['model.restaurant']->troisDerniersRestau();
	$trenteProchainsJours = $app['model.recherche']->trenteProchainsJours();
	$listeCategories = $app['model.recherche']->listeCategories();
	// Appel de la vue page d'accueil
    return $app['twig']->render('index.html.twig', array('listeRestau' => $troisDerniersRestau, 'date' => $trenteProchainsJours, 'listeCat' => $listeCategories));
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

// Espace restaurateur
$app->get('/espace-restaurateur', function () use ($app) {
	return $app['twig']->render('espace-restaurateur.html.twig');
})->bind('espace_restau');

// Recherche restau
$app->post('/recherche-restaurant', function (Request $request) use ($app) {
	$listeRestau = $app['model.recherche']->rechercheRestau($request);
	return $app['twig']->render('liste-restaurant.html.twig', array('listeRestau' => $listeRestau));
})->bind('recherche_restau');

////////////////////////////
///// RESERVATIONS /////////
////////////////////////////

// Mes reservations
$app->post('/mes-reservations', function (Request $request) use ($app) {
	$result = $app['model.reservation']->rechercheReservation($request);
	return $app['twig']->render('mes-reservations.html.twig', array('result' => $result));
})->bind('mes_reservations');

// Recherche reservation
$app->get('/mes-reservations/recherche', function (Request $request) use ($app) {
	return $app['twig']->render('formulaire/mes-reservations.html.twig');
})->bind('recherche_reservations');

// Modif reservations
$app->post('/modif-resa', function (Request $request) use ($app) {
	$result = $app['model.reservation']->modifReservation($request);
	$app['session']->getFlashBag()->add('msg_success', 'La réservation a bien été mise à jour ;)');
	return $app['twig']->render('mes-reservations.html.twig', array('result' => $result));
})->bind('modif_resa');

// Supprimer reservation
$app->get('/suppr-resa/{id}', function ($id) use ($app) {
	$result = $app['model.reservation']->supprReservation($id);
	if ($result) {
		$app['session']->getFlashBag()->add('msg_suppr', 'La réservation a bien été supprimée ;)');
		return $app->redirect($app["url_generator"]->generate("home"));
	} else {
		$app['session']->getFlashBag()->add('msg_suppr', 'Une erreur est survenue lors de la supression, veuillez réessayer :(');
		return $app['twig']->render('formulaire/mes-reservations.html.twig');
	}
})->bind('suppr_resa');

