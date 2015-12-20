<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('home.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        View::make('helloworld.html');
    }

    public static function login() {
        View::make('login.html');
    } 
    public static function kokeilu() {
        View::make('kokeilu.html');
    }

    public static function langat() {
        View::make('langat.html');
    }
     public static function muokkaa() {
        View::make('muokkaa.html');
    }

    public static function tili() {
        View::make('tili.html');
    }

}
