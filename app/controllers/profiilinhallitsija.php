<?php

class profiilinhallitsija extends BaseController {

    public function luoSivu() {
        if (self::get_user_logged_in()) {
            $tili = self::get_user_logged_in();
            $nimi = $tili->name;
            $viestit = viesti::getByAuthor($tili->id);
            View::make('tili/tili.html', array('viestit' => $viestit, 'nimi' => $nimi));
        } else {
            Redirect::to('/login/', array('message' => 'Kirjaudu sisään nähdäksesi profiilisi!'));
        }
    }

    public function loginSivu() {
        View::make('tili/login.html');
    }

    public function tunnuksetPeliin() {
        $params = $_POST;
        $user = tili::autentikoi($params['nimi'], $params['salasana']);

        if (!$user) {
            View::make('tili/tili.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['nimi']));
        } else {
            $_SESSION['user'] = $user->id;
            Redirect::to('/', array('message' => 'Teretulemast takeesi ' . $user->name . '!'));
        }
    }
    
    public function vaihdaNimi() {
        
    }
    
    public function vaihdaSalasana() {
        
    }

}
