<?php

class keskustelualueenluoja extends BaseController {

    public function luoAliFoorumit() {
        $keskustelualue = keskustelualue::all();
        View::make('keskustelualueet/keskustelualueet.html', array('keskustelualueet' => $keskustelualue));
    }

    public function luoAliFoorumi($id) {
        $keskustelualue = keskustelu::getBySubForum($id);
        View::make('keskustelualueet/langat.html', array('keskustelualue' => $keskustelualue));
    }

    public function luoLanka($id) {
        $viestit = viesti::getByThread($id);

        $lankaid = $id;
        View::make('keskustelualueet/lanka.html', array('viestit' => $viestit, 'lankaid' => $lankaid));
    }

    public function luoUusiKetju($id) {
        if (self::get_user_logged_in()) {
            $subforum = keskustelualue::subforumByID($id);
            View::make('keskustelualueet/uusiketju.html', array('subforum' => $subforum));
        } else {
            Redirect::to('/login/', array('message' => 'Kirjaudu sisään osallistuaksesi keskusteluun'));
        }
    }

    public function luoViesti($id) {
        if (self::get_user_logged_in()) {
            $params = $_POST;
            $tili = self::get_user_logged_in();
            $attributes = array(
                'content' => $_POST['viesti'],
                'author' => $tili->id,
                'time' => date('d M Y H:i:s'),
                'thread' => $id
            );
            $viesti = new viesti($attributes);
            $errors = $viesti->errors();
            if (count($errors) == 0) {
                $viesti->save();
            } else {
                Redirect::to('/uusi/viesti/' . $viesti->thread, array('errors' => $errors, 'params' => $params));
            }
            Redirect::to('/langat/' . $viesti->thread, array('message' => 'Viesti luotu onistunteesti'));
        } else {
            Redirect::to('/login/', array('message' => 'Kirjaudu sisään osallistuaksesi keskusteluun'));
        }
    }

    public function luoUusiViesti($id) {
        if (self::get_user_logged_in()) {
            $keskustelu = keskustelu::getThreadById($id);
            View::make('keskustelualueet/uusiviesti.html', array('keskustelu' => $keskustelu));
        } else {
            Redirect::to('/login/', array('message' => 'Kirjaudu sisään osallistuaksesi keskusteluun'));
        }
    }

    public function muokkaaViestia($id) {
        $message = viesti::getByID($id);
        View::make('/keskustelualueet/muokkaa.html', array('viesti' => $message));
    }

    public function tapaKetju($id) {
        $lanka = keskustelu::getThreadById($id);
        $lanka = new keskustelu(array(
            'id' => $id
                )
        );
        $lanka->delete();
        Redirect::to('/aiheet/', array('message' => 'Viesti poistettu onnistuneesti!'));
    }

    public function tapaViesti($id) {
        $row = viesti::getRow($id);
        $lanka = $row['thread'];
        $viesti = new Viesti(array(
            'id' => $row['id'],
            'content' => $row['content'],
            'time' => $row['time'],
            'author' => $row['author'],
            'thread' => $row['thread']
        ));
        $viesti->delete();
        Redirect::to('/langat/' . $lanka, array('message' => 'Viesti poistettu onnistuneesti!'));
    }

    public function luoMuokattuViesti($id) {
        $params = $_POST;
        $viesti = viesti::getRow($id);
        $attributes = array(
            'id' => $id,
            'content' => $params['viesti'],
            'time' => $viesti['time'],
            'author' => $viesti['author'],
            'thread' => $viesti['thread']
        );
        $message = new viesti($attributes);
        $errors = $message->errors();
        if (count($errors) == 0) {
            $message->update();
        } else {
            Redirect::to('/viesti/edit/' . $id, array('errors' => $errors, 'params' => $params));
        }
        Redirect::to('/langat/' . $viesti['thread'], array('message' => 'Viesti muokattu onnistuneesti!'));
    }

    public function luoKetjunEditoija($id) {
        $keskustelu = keskustelu::getThreadById($id);
        View::make('/keskustelualueet/muokkaaKetjua.html', array('keskustelu' => $keskustelu));
    }

    public function editoiKetjua($id) {
        $params = $_POST;
        $tili = self::get_user_logged_in();
        $ketju = keskustelu::getThreadById($id);
        $attributes = array(
            'id' => $id,
            'topic' => $_POST['viesti']
        );
        $keskustelu = new keskustelu($attributes);
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
        $ketju = new keskustelu($attributes);
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
        $viesti = new viesti($attributes);
        $errors = $viesti->errors();
        if (count($errors) == 0) {
            $viesti->save();
        } else {
            Redirect::to('/uusi/ketju/' . $id, array('errors' => $errors, 'params' => $params));
        }
        Redirect::to('/langat/' . $ketju->id, array('message' => 'Lanka luotu onnistuneesti!'));
    }

}
