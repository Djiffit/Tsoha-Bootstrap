<?php

class profiilinhallitsija extends BaseController {
    
    public static function luoSivu($id) {
        $nimi = tili::getUserByID($id);
        $viestit = viesti::getByAuthor($id);
        View::make('tili/tili.html', array('viestit' => $viestit), $nimi);
        
    }
}

