<?php

class ViestinHallitsija extends BaseController {

    public function luoViesti($id) {
        $params = $_POST;
        $tili = self::get_user_logged_in();
        $attributes = array(
            'content' => $_POST['viesti'],
            'author' => $tili->id,
            'time' => date('d M Y H:i:s'),
            'thread' => $id
        );
        $viesti = new Viesti($attributes);
        $errors = $viesti->errors();
        if (count($errors) == 0) {
            $viesti->save();
        } else {
            Redirect::to('/uusi/viesti/' . $viesti->thread, array('errors' => $errors, 'params' => $params));
        }
        Redirect::to('/langat/' . $viesti->thread, array('message' => 'Viesti luotu onistunteesti'));
    }

    public function luoUusiViesti($id) {
        if (self::check_logged_in()) {
            $keskustelu = Keskustelu::keskusteluIdAvulla($id);
            View::make('keskustelualueet/uusiviesti.html', array('keskustelu' => $keskustelu));
        } else {
            Redirect::to('/login/', array('message' => 'Kirjaudu sisään osallistuaksesi keskusteluun'));
        }
    }

    public function muokkaaViestia($id) {
        if (self::check_logged_in()) {
            $message = Viesti::haeIDlla($id);
            View::make('/keskustelualueet/muokkaa.html', array('viesti' => $message));
        } else {
            Redirect::to('/login/', array('message' => 'Kirjaudu sisään tilillesi!'));
        }
    }

    public function tapaViesti($id) {
        if (self::check_logged_in()) {
            $row = Viesti::getRow($id);
            $lanka = $row['thread'];
            $viesti = new Viesti(array(
                'id' => $row['id'],
                'author' => $row['author']
            ));
            if ($viesti->author == self::get_user_logged_in()->id || self::get_user_logged_in()->moderator == 1) {
                $viesti->delete();
                Redirect::to('/langat/' . $lanka, array('message' => 'Viesti poistettu onnistuneesti!'));
            } else {
                Redirect::to('/', array('message' => 'Ei valtuuksia toimintoon!'));
            }
        } else {
            Redirect::to('/login', array('message' => 'Kirjaudu sisään tilillesi!'));
        }
    }

    public function luoMuokattuViesti($id) {
        $params = $_POST;
        $viesti = Viesti::getRow($id);
        $attributes = array(
            'id' => $id,
            'content' => $params['viesti'],
            'thread' => $viesti['thread']
        );
        $message = new Viesti($attributes);
        $errors = $message->errors();
        if (count($errors) == 0) {
            $message->update();
        } else {
            Redirect::to('/viesti/edit/' . $id, array('errors' => $errors, 'params' => $params));
        }
        Redirect::to('/langat/' . $viesti['thread'], array('message' => 'Viesti muokattu onnistuneesti!'));
    }

}
