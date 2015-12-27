<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('home.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        $keskustelu = keskustelu::getTopic(5);
        Kint::dump($keskustelu);
        $keskustelu = keskustelu::getThreadById(5);
        Kint::dump($keskustelu);
        $keskustelualue = keskustelualue::all();
        Kint::dump($keskustelualue);
        $keskustelu = keskustelu::getBeforeDate("'2015-12-30'");
        Kint::dump($keskustelu);
        $keskustelu = keskustelu::getBySubForum(2);
        Kint::dump($keskustelu);
        $tili = tili::all();
        Kint::dump($tili);
        $tili = tili::getUserByID(2);
        Kint::dump($tili);
        $tili = tili::getUserByName('Pekka');
        $viesti = viesti::getByAuthor(2);
        Kint::dump($viesti);
        $viesti = viesti::getMostRecent();
        Kint::dump($viesti);
        // Kint-luokan dump-metodi tulostaa muuttujan arvon


        Kint::dump($tili);
        $pekka = new tili(array(
            'name' => '..q',
            'password' => ' ..'
        ));
        $errors = $pekka->errors();

        Kint::dump($errors);
        $pekka = new viesti(array(
            'content' => '.q',
            'thread' => ''
        ));
        $errors = $pekka->errors();

        Kint::dump($errors);
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
