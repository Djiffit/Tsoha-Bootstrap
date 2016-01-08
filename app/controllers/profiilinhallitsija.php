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

    public function luoTunnus() {
        $params = $_POST;
        $user = tili::autentikoi($params['nimi'], $params['salasana']);
        $tili = new tili(array(
            'name' => $params['nimi'],
            'password' => $params['salasana']
        ));
        $errors = $tili->errors();
        if (count($errors) == 0) {
            $tili->save();
            $_SESSION['user'] = $tili->id;
            Redirect::to('/', array('message' => 'Tunnuksen luonti onnistunut!'));
        } else {
            Redirect::to('/login', array('errors' => $errors, 'muuttujat' => $params));
        }
    }

    public function suosikinPoistaja() {
        $id = $_POST['id'];
        $tili = self::get_user_logged_in();
        $suosikki = new suosikki(array(
            'userid' => $tili->id,
            'threadid' => $id
        ));
        $suosikki->delete();
        Redirect::to('/suosikit', array('message' => 'Suosikin poisto onnistunut!'));
    }

    public function lisaaSuosikki() {
        $id = $_POST['id'];
        $suosikki = new suosikki(array(
            'userid' => self::get_user_logged_in()->id,
            'threadid' => $id
        ));
        $suosikki->save();
        Redirect::to('/langat/' . $id, array('message' => 'Suosikki lisätty!'));
    }

    public function suosikinLuoja() {
        if (self::get_user_logged_in()) {
            $tili = self::get_user_logged_in();
            $keskustelut = suosikki::getSuosikitID($tili->id);
            View::make('tili/suosikit.html', array('keskustelut' => $keskustelut));
        } else {
            Redirect::to('/login/', array('message' => 'Kirjaudu sisään nähdäksesi suosikkisi!'));
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

    public function kirjauduUlos() {
        $_SESSION['user'] = null;
        Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
    }

    public function suosikinPoisto() {
        $id = $_POST['id'];
        $tili = self::get_user_logged_in();
        $suosikki = new suosikki(array(
            'userid' => $tili->id,
            'threadid' => $id
        ));
        $suosikki->delete();
        Redirect::to('/langat/' . $id, array('message' => 'Suosikin poisto onnistunut!'));
    }

}
