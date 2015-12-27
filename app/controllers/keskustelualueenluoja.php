<?php

class keskustelualueenluoja {

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
        View::make('keskustelualueet/lanka.html', array('viestit' => $viestit));
    }

    public function luoUusiKetju($id) {
        $subforum = keskustelualue::subforumByID($id);
        View::make('keskustelualueet/uusiketju.html', array('subforum' => $subforum));
    }

    public function luoViesti($id) {
    $params = $_POST;
    $viesti = new viesti( array(
        'author' => rand(1,11),
        'content' => $_POST['viesti'],
        'thread' => $id,
        'time' =>date('d M Y H:i:s')
    ));
       $viesti->save();
       Redirect::to('/langat/'.$id, array('message' => 'Viesti luotu onistunteestisdaf'));
        
    }

    public function luoUusiViesti($id) {
        $keskustelu = keskustelu::getThreadById($id);
        View::make('keskustelualueet/uusiviesti.html', array('keskustelu' => $keskustelu));
    }

    public function luoKetju($id) {
        $params = $_POST;
        $ketju = new keskustelu(array(
            'topic' => $_POST['otsikko'],
            'starter' => rand(1, 10),
            'time' => date('d M Y H:i:s'),
            'subforum' => $id
        ));
        
        $ketju->save();
        $viesti = new viesti(array(
            'content' => $_POST['viesti'],
            'author' => rand(1, 10),
            'time' => date('d M Y H:i:s'),
            'thread' => $ketju->id
        ));
        $viesti->save();
        Redirect::to('/langat/' . $ketju->id, array('message' => 'Lanka luotu onnistuneesti!'));
    }

}
