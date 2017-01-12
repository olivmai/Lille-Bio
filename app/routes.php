<?php

// Home page
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('home');

// Liste des dragons
$app->get('/dragon-liste', function () use ($app) {
	// Requete liste dragon
	$sql_liste_dragon = "SELECT * FROM dragon";
	$dragons = $app['db']->fetchAll($sql_liste_dragon);
    // Envoi des résultats au template Twig
    return $app['twig']->render('dragon/liste.html.twig', array('dragons' => $dragons));
})->bind('dragon_liste');