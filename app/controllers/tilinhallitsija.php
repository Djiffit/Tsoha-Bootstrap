<?php

class TilinHallitsija extends BaseController {

    public function luoSivu() {
        if (self::get_user_logged_in()) {
            $tili = self::get_user_logged_in();
            $nimi = $tili->name;
            $viestit = Viesti::haeTekijalla($tili->id);
            View::make('tili/tili.html', array('viestit' => $viestit, 'nimi' => $nimi));
        } else {
            Redirect::to('/login/', array('message' => 'Kirjaudu sisään nähdäksesi profiilisi!'));
        }
    }

    public function poistaTili() {
        $tieto = $_POST;
        $tili = new Tili(array(
            'id' => $tieto['id']
        ));
        $tili->delete();
        Redirect::to('/tilit', array('message' => 'Tili poistettu onnistuneesti!'));
    }

    public function uusiNimi() {
        $tieto = $_POST;
        $tili = new Tili(array(
            'id' => self::get_user_logged_in()->id,
            'name' => $tieto['nimi'],
            'password' => self::get_user_logged_in()->password
        ));
        $errors = $tili->errors();
        if (count($errors) == 0) {
            $tili->paivitaNimi($tili->name);
            Redirect::to('/tili', array('message' => 'Nimi vaihdettu!'));
        } else {
            Redirect::to('/tili', array('errors' => $errors, 'tieto' => $tieto));
        }
    }

    public function uusiSalasana() {
        $tieto = $_POST;
        $tili = new Tili(array(
            'id' => self::get_user_logged_in()->id,
            'name' => rand(0, 999999),
            'password' => $tieto['salasana']
        ));
        $errors = $tili->errors();
        if (count($errors) == 0) {
            $tili->paivitaSalasana($tili->password);
            Redirect::to('/tili', array('message' => 'Salasana vaihdettu!'));
        } else {
            Redirect::to('/tili', array('errors' => $errors, 'tieto' => $tieto));
        }
    }

    public function luoTunnus() {
        $params = $_POST;
        $user = Tili::autentikoi($params['nimi'], $params['salasana']);
        $tili = new Tili(array(
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
        $suosikki = new Suosikki(array(
            'userid' => $tili->id,
            'threadid' => $id
        ));
        $suosikki->delete();
        Redirect::to('/suosikit', array('message' => 'Suosikin poisto onnistunut!'));
    }

    public function lisaaSuosikki() {
        $id = $_POST['id'];
        $suosikki = new Suosikki(array(
            'userid' => self::get_user_logged_in()->id,
            'threadid' => $id
        ));
        $suosikki->save();
        Redirect::to('/langat/' . $id, array('message' => 'Suosikki lisätty!'));
    }

    public function suosikinLuoja() {
        if (self::get_user_logged_in()) {
            $tili = self::get_user_logged_in();
            $keskustelut = Suosikki::getSuosikitID($tili->id);
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
        $user = Tili::autentikoi($params['nimi'], $params['salasana']);

        if ($user) {
            $_SESSION['user'] = $user->id;
            Redirect::to('/', array('message' => 'Teretulemast takeesi ' . $user->name . '!'));
        } else {
            Redirect::to('/login/', array('message' => 'Väärä käyttäjätunnus tai salasana! Yritä uudestaan!', 'tiedot' => $params));
        }
    }

    public function kirjauduUlos() {
        $_SESSION['user'] = null;
        Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
    }

    public function suosikinPoisto() {
        $id = $_POST['id'];
        $tili = self::get_user_logged_in();
        $suosikki = new Suosikki(array(
            'userid' => $tili->id,
            'threadid' => $id
        ));
        $suosikki->delete();
        Redirect::to('/langat/' . $id, array('message' => 'Suosikin poisto onnistunut!'));
    }

    public function listaaTilit() {
        $tilit = Tili::all();
        View::make('tili/kayttajat.html', array('tilit' => $tilit));
    }

}
