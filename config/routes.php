<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/tili', function() {
    HelloWorldController::tili();
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
