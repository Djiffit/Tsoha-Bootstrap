<?php

$routes->get('/', function() {
    etusivunvalitsija::index();
});



$routes->get('/sandbox', function() {
    HelloWorldController::sandbox();
});

$routes->get('/tili/', function() {
    profiilinhallitsija::luoSivu();
});

$routes->get('/keskustelualueet/:id', function($id) {
    keskustelualueenluoja::luoAliFoorumi($id);
});

$routes->post('/langat/:id', function($id) {
    keskustelualueenluoja::luoKetju($id);
});

$routes->get('/lanka/edit/:id', function($id) {
    keskustelualueenluoja::luoKetjunEditoija($id);
});

$routes->post('/lanka/edit/:id', function($id) {
    keskustelualueenluoja::editoiKetjua($id);
});

$routes->get('/lanka/kill/:id', function($id) {
    keskustelualueenluoja::tapaKetju($id);
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

$routes->get('/viesti/edit/:id', function($id) {
    keskustelualueenluoja::muokkaaViestia($id);
});

$routes->get('/viesti/kill/:id', function($id) {
    keskustelualueenluoja::tapaViesti($id);
});

$routes->post('/viesti/edit/:id', function($id) {
    keskustelualueenluoja::luoMuokattuViesti($id);
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

$routes->post('/login/', function() {
    profiilinhallitsija::tunnuksetPeliin();
});

$routes->get('/login/', function() {
    profiilinhallitsija::loginSivu();
});

$routes->get('/tili/muokkaa', function() {
    HelloWorldController::muokkaa();
});
