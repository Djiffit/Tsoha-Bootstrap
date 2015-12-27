<?php

$routes->get('/', function() {
etusivunvalitsija::index();
});



$routes->get('/sandbox', function() {
HelloWorldController::sandbox();
});

$routes->get('/tili/:id', function($id) {
profiilinhallitsija::luoSivu($id);
});

$routes->get('/keskustelualueet/:id', function($id) {
keskustelualueenluoja::luoAliFoorumi($id);
});

$routes->post('/langat/:id', function($id) {
keskustelualueenluoja::luoKetju($id);
});

$routes->get('/uusi/ketju/:id', function($id) {
keskustelualueenluoja::luoUusiKetju($id);
});

$routes->get('/uusi/viesti/:id', function($id) {
keskustelualueenluoja::luoUusiViesti($id);
});

$routes->post('/lanka/:id', function($id) {
keskustelualueenluoja::luoViesti($id);
});

$routes->get('/aiheet/', function() {
keskustelualueenluoja::luoAliFoorumit();
});

$routes->get('/aiheet/:id', function($id) {
keskustelualueenluoja::luoAliFoorumi($id);
});

$routes->get('/langat/:id', function($id) {
keskustelualueenluoja::luoLanka($id);
});

$routes->get('/login', function() {
HelloWorldController::login();
});

$routes->get('/langat', function() {
HelloWorldController::langat();
});

$routes->get('/langat/kokeilu', function() {
HelloWorldController::kokeilu();
});
$routes->get('/tili/muokkaa', function() {
HelloWorldController::muokkaa();
});
