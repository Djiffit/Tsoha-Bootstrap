<?php

class EtusivunHallitsija extends BaseController {
    
    public static function index() {
        $viestit = Viesti::tuoreimmat();
        View::make('home.html', array('viestit' => $viestit));
    }
}

