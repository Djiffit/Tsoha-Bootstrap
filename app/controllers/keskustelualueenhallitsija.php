<?php

class KeskustelualueenHallitsija extends BaseController {

    public function luoAliFoorumit() {
        $keskustelualue = Keskustelualue::all();
        View::make('keskustelualueet/keskustelualueet.html', array('keskustelualueet' => $keskustelualue));
    }

    public function luoAliFoorumi($id) {
        $keskustelualue = Keskustelu::keskusteluAlifooruminAvulla($id);
        View::make('keskustelualueet/langat.html', array('keskustelualue' => $keskustelualue));
    }

    public function luoLanka($id) {
        $viestit = Viesti::haeLangalla($id);
        $lankaid = $id;
        if (self::get_user_logged_in()) {
            $suosikki = Suosikki::isSuosikki(self::get_user_logged_in()->id, $id);
            View::make('keskustelualueet/lanka.html', array('viestit' => $viestit, 'lankaid' => $lankaid, 'suosikki' => $suosikki));
        } else {
            View::make('keskustelualueet/lanka.html', array('viestit' => $viestit, 'lankaid' => $lankaid));
        }
    }

    public function luoUusiKetju($id) {
        if (self::get_user_logged_in()) {
            $subforum = Keskustelualue::alifoorumiIDlla($id);
            View::make('keskustelualueet/uusiketju.html', array('subforum' => $subforum));
        } else {
            Redirect::to('/login/', array('message' => 'Kirjaudu sisään osallistuaksesi keskusteluun'));
        }
    }

    public function tapaKetju($id) {
        if (self::check_logged_in()) {
            $keskustelu = Keskustelu::keskusteluIdAvulla($id);
            if ($keskustelu[0]->starter == self::get_user_logged_in()->id || self::get_user_logged_in()->moderator == 1) {
                $lanka = new Keskustelu(array(
                    'id' => $id
                ));
                $lanka->delete();
                Redirect::to('/aiheet/', array('message' => 'Ketju poistettu onnistuneesti!'));
            } else {
                Redirect::to('/', array('message' => 'Ei valtuuksia toimintoon'));
            }
        } else {
            Redirect::to('/login/', array('message' => 'Kirjaudu sisään tilillesi!'));
        }
    }

    public function luoKetjunEditoija($id) {
        if (self::check_logged_in()) {
            $keskustelu = Keskustelu::keskusteluIdAvulla($id);
            if ($keskustelu[0]->starter == self::get_user_logged_in()->id || self::get_user_logged_in()->moderator == 1) {
                View::make('/keskustelualueet/muokkaaKetjua.html', array('keskustelu' => $keskustelu));
            } else {
                Redirect::to('/', array('message' => 'Ei valtuuksia toimintoon!'));
            }
        } else {
            Redirect::to('/login/', array('message' => 'Kirjaudu sisään tunnuksellesi!'));
        }
    }

    public function editoiKetjua($id) {
        $params = $_POST;
        $tili = self::get_user_logged_in();
        $ketju = Keskustelu::keskusteluIdAvulla($id);
        $attributes = array(
            'id' => $id,
            'topic' => $_POST['viesti']
        );
        $keskustelu = new Keskustelu($attributes);
        $errors = $keskustelu->errors();
        if (count($errors) == 0) {
            $keskustelu->update();
        } else {
            Redirect::to('/lanka/edit/' . $id, array('errors' => $errors, 'params' => $params));
        }
        Redirect::to('/langat/' . $id, array('message' => 'Otsikko muokattu onnistuneesti!'));
    }

    public function luoKetju($id) {
        $params = $_POST;
        $tili = self::get_user_logged_in();
        $attributes = array('topic' => $_POST['otsikko'],
            'starter' => $tili->id,
            'time' => date('d M Y H:i:s'),
            'subforum' => $id);
        $ketju = new Keskustelu($attributes);
        $errors = $ketju->errors();
        if (count($errors) == 0) {
            $ketju->save();
        } else {
            Redirect::to('/uusi/ketju/' . $id, array('errors' => $errors, 'params' => $params));
        }
        $attributes = array(
            'content' => $_POST['viesti'],
            'author' => $tili->id,
            'time' => date('d M Y H:i:s'),
            'thread' => $ketju->id
        );
        $viesti = new Viesti($attributes);
        $errors = $viesti->errors();
        if (count($errors) == 0) {
            $viesti->save();
        } else {
            $ketju->delete();
            Redirect::to('/uusi/ketju/' . $id, array('errors' => $errors, 'params' => $params));
        }
        Redirect::to('/langat/' . $ketju->id, array('message' => 'Lanka luotu onnistuneesti!'));
    }

}
