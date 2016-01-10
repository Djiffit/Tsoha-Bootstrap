<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('home.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        $keskustelu = Keskustelu::getTopic(80);
        Kint::dump($keskustelu);
        $keskustelu = Keskustelu::keskusteluIdAvulla(78);
        Kint::dump($keskustelu);
        $keskustelualue = Keskustelualue::all();
        Kint::dump($keskustelualue);
        $keskustelu = Keskustelu::keskusteluAlifooruminAvulla(2);
        Kint::dump($keskustelu);
        $tili = Tili::all();
        Kint::dump($tili);
        $tili = Tili::getKayttajaIDlla(2);
        Kint::dump($tili);
        $viesti = Viesti::haeTekijalla(2);
        Kint::dump($viesti);
        $viesti = Viesti::tuoreimmat();
        Kint::dump($viesti);
        $viesti = Viesti::haeIDlla(211);
        Kint::dump($viesti);
        Kint::dump($viesti[0]->id);
        // Kint-luokan dump-metodi tulostaa muuttujan arvon
//        Kint::dump($tili);
//        $pekka = new tili(array(
//            'name' => '..q',
//            'password' => ' ..'
//        ));
//        $errors = $pekka->errors();
//        Kint::dump($errors);
//        $pekka = new viesti(array(
//            'content' => '.q',
//            'thread' => ''
//        ));
//        $errors = $pekka->errors();
//
//        Kint::dump($errors);

        $keskustelu = new Keskustelu(array(
            'topic' => '12'
        ));
        $errors = $keskustelu->errors();
        Kint::dump($errors);
        $suosikki = Suosikki::getSuosikitID(1);
        Kint::dump($suosikki);
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
