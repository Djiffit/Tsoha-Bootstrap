<?php

class etusivunvalitsija extends BaseController {
    
    public static function index() {
        $viestit = viesti::getMostRecent();
        View::make('home.html', array('viestit' => $viestit));
    }
}

