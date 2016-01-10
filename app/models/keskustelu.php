<?php

class Keskustelu extends BaseModel {

    public $id, $topic, $subforum, $starter, $time;

    public function __construct($attributes) {
        parent::__construct($attributes);
        
        $this->validators = array('validateTopic');
    }

    public static function getTopic($id) {
        $query = DB::connection()->prepare('SELECT * FROM thread where id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        return $row['topic'];
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Thread order by time desc');
        $query->execute();
        $rows = $query->fetchAll();
        $keskustelut = array();
        foreach ($rows as $row) {
            $keskustelut[] = new Keskustelu(array(
                'id' => $row['id'],
                'topic' => $row['topic'],
                'subforum' => $row['subforum'],
                'starter' => Tili::getKayttajaIDlla($row['starter']),
                'time' => $row['time']
            ));
        }
        return $keskustelut;
    }

    public static function keskusteluIdAvulla($id) {
        $query = DB::connection()->prepare('SELECT * FROM Thread where id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        $keskustelu[] = new Keskustelu(array(
            'id' => $row['id'],
            'topic' => $row['topic'],
            'subforum' => Keskustelualue::alifoorumiNimiIdlla($row['subforum']),
            'time' => $row['time'],
            'starter' => $row['starter']
        ));
        return $keskustelu;
    }

    public static function keskusteluAlifooruminAvulla($id) {
        $query = DB::connection()->prepare('SELECT * FROM Thread where subforum = :id order by time desc');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $keskustelut = array();
        foreach ($rows as $row) {
            $keskustelut[] = new Keskustelu(array(
                'id' => $row['id'],
                'topic' => $row['topic'],
                'subforum' => $row['subforum'],
                'starter' => Tili::getKayttajaIDlla($row['starter']),
                'time' => $row['time']
            ));
        }
        return $keskustelut;
    }

    public static function keskusteluAloittajanAvulla($id) {
        $query = DB::connection()->prepare('SELECT * FROM Thread where starter = :id order by time desc');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $keskustelut = array();
        foreach ($rows as $row) {
            $keskustelut[] = new Keskustelu(array(
                'id' => $row['id'],
                'topic' => $row['topic'],
                'subforum' => $row['subforum'],
                'starter' => $row['starter'],
                'time' => $row['time']
            ));
        }
        return $keskustelut;
    }

    public function validateTopic() {
        $errors[] = array();
        if (!BaseModel::validateLength($this->topic, 5, 50)) {
            $errors[] = 'Otsikon tulee olla ainakin 5 merkkiä ja alle 50 merkkiä pitkä';
        }
        return $errors;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Thread (subforum, time, topic, starter) VALUES (:subforum, :time, :topic, :starter) RETURNING id');
        $query->execute(array('subforum' => $this->subforum, 'time' => $this->time, 'topic' => $this->topic, 'starter' => $this->starter));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public function update() {
        $query = DB::connection()->prepare('UPDATE thread SET topic = :topic where id= :id;');
        $query->execute(array('id' => $this->id, 'topic' => $this->topic));
    }

    public function delete() {
        $query = DB::connection()->prepare('DELETE from favorite where threadid = :id');
        $query->execute(array('id' => $this->id));
        $query = DB::connection()->prepare('DELETE from message where thread = :id');
        $query->execute(array('id' => $this->id));
        $query = DB::connection()->prepare('DELETE from thread where id=:id');
        $query->execute(array('id' => $this->id));
    }

}
