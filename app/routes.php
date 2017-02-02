<?php

// Home page
$app->get('/', function () use ($app) {
	// Requete qui récupère les trois derniers restau avec leurs images et catégories
	$troisDerniersRestau = $app['model.restaurant']->troisDerniersRestau();
	// Appel de la vue page d'accueil
    return $app['twig']->render('index.html.twig', array('listeRestau' => $troisDerniersRestau));
})->bind('home');

// Aide utilisateurs
$app->get('/aide-utilisateurs', function () use ($app) {
	return $app['twig']->render('aide-utilisateur.html.twig');
})->bind('aide_utilisateurs');

// Restaurant
$app->get('/restaurant', function () use ($app) {
	return $app['twig']->render('restaurant.html.twig');
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


