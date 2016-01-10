<?php

$routes->get('/', function() {
    EtusivunHallitsija::index();
});

$routes->get('/sandbox', function() {
    HelloWorldController::sandbox();
});

$routes->get('/tili/', function() {
    TilinHallitsija::luoSivu();
});

$routes->get('/keskustelualueet/:id', function($id) {
    KeskustelualueenHallitsija::luoAliFoorumi($id);
});

$routes->get('/logout', function() {
    TilinHallitsija::kirjauduUlos();
});

$routes->post('/langat/:id', function($id) {
    KeskustelualueenHallitsija::luoKetju($id);
});

$routes->post('/langat/:id', function($id) {
    KeskustelualueenHallitsija::luoKetju($id);
});

$routes->post('/suosikit/lisaa', function() {
    TilinHallitsija::lisaaSuosikki();
});

$routes->post('/lanka/edit/:id', function($id) {
    KeskustelualueenHallitsija::editoiKetjua($id);
});

$routes->post('/suosikit/poista', function() {
    TilinHallitsija::suosikinPoisto();
});

$routes->post('/login/register', function() {
    TilinHallitsija::luoTunnus();
});

$routes->get('/lanka/edit/:id', function($id) {
    KeskustelualueenHallitsija::luoKetjunEditoija($id);
});

$routes->get('/lanka/kill/:id', function($id) {
    KeskustelualueenHallitsija::tapaKetju($id);
});

$routes->get('/tilit/', function() {
    TilinHallitsija::listaaTilit();
});

$routes->get('/uusi/ketju/:id', function($id) {
    KeskustelualueenHallitsija::luoUusiKetju($id);
});

$routes->get('/uusi/viesti/:id', function($id) {
    ViestinHallitsija::luoUusiViesti($id);
});

$routes->post('/lanka/:id', function($id) {
    ViestinHallitsija::luoViesti($id);
});

$routes->get('/viesti/edit/:id', function($id) {
    ViestinHallitsija::muokkaaViestia($id);
});

$routes->get('/viesti/kill/:id', function($id) {
    ViestinHallitsija::tapaViesti($id);
});

$routes->post('/viesti/edit/:id', function($id) {
    ViestinHallitsija::luoMuokattuViesti($id);
});

$routes->post('/tilit/kill', function() {
    TilinHallitsija::poistaTili();
});

$routes->post('/tili/uusisalasana', function() {
    TilinHallitsija::uusiSalasana();
});

$routes->post('/tili/uusinimi', function() {
    TilinHallitsija::uusiNimi();
});

$routes->get('/aiheet/', function() {
    KeskustelualueenHallitsija::luoAliFoorumit();
});

$routes->get('/aiheet/:id', function($id) {
    KeskustelualueenHallitsija::luoAliFoorumi($id);
});

$routes->get('/langat/:id', function($id) {
    KeskustelualueenHallitsija::luoLanka($id);
});

$routes->get('/langat', function() {
    HelloWorldController::langat();
});

$routes->get('/langat/kokeilu', function() {
    HelloWorldController::kokeilu();
});

$routes->post('/login/', function() {
    TilinHallitsija::tunnuksetPeliin();
});

$routes->get('/login/', function() {
    TilinHallitsija::loginSivu();
});

$routes->get('/suosikit/', function() {
    TilinHallitsija::suosikinLuoja();
});

$routes->post('/suosikit/', function() {
    TilinHallitsija::suosikinPoistaja();
});

$routes->get('/tili/muokkaa', function() {
    HelloWorldController::muokkaa();
});
