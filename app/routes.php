<?php

// Home page
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('home');

// Liste des dragons
$app->get('/dragon-liste', function () use ($app) {
	// Requete liste dragon
	$dragons = $app['model.dragon']->listeDragons();
    // Envoi des résultats au template Twig
    return $app['twig']->render('dragon/liste.html.twig', array('dragons' => $dragons));
})->bind('dragon_liste');

// Aide utilisateurs
$app->get('/aide-utilisateurs', function () use ($app) {
	return $app['twig']->render('aide-utilisateur.html.twig');
})->bind('aide_utilisateurs');



