<?php

class viesti extends BaseModel {

    public $id, $content, $time, $author, $thread;

    public function __construct($attributes) {
        parent::__construct($attributes);

        $this->validators = array('validateContent');
    }
    

    public static function getByID($id) {
        $query = DB::connection()->prepare('SELECT * FROM message where id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        $viestit[] = new viesti(array(
            'id' => $row['thread'],
            'content' => $row['content'],
            'time' => $row['time'],
            'author' => $row['id'],
            'thread' => keskustelu::getTopic($row['thread'])
        ));

        return $viestit;
    }
    
        public static function getRow($id) {
        $query = DB::connection()->prepare('SELECT * FROM message where id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        return $row;
    }

    public static function getByThread($id) {
        $query = DB::connection()->prepare('SELECT * FROM message where thread = :id');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $viestit = array();
        foreach ($rows as $row) {

            $viestit[] = new viesti(array(
                'id' => $row['id'],
                'content' => $row['content'],
                'time' => $row['time'],
                'author' => tili::getUserByID($row['author']),
                'thread' => keskustelu::getTopic($row['thread'])
            ));
        }
        return $viestit;
    }

    public static function onkoKaytetty($row, $kaytetty) {
        if (in_array($row['thread'], $kaytetty)) {
            return false;
        } else {
            return true;
        }
    }

    public static function getMostRecent() {
        $query = DB::connection()->prepare('SELECT * FROM message order by time desc LIMIT 50');
        $query->execute();
        $rows = $query->fetchAll();
        $viestit = array();
        $kaytetyt = array();
        foreach ($rows as $row) {
            if (viesti::onkoKaytetty($row, $kaytetyt) && sizeof($kaytetyt) < 20) {
                array_push($kaytetyt, $row['thread']);
                $viestit[] = new viesti(array(
                    'id' => $row['thread'],
                    'content' => $row['content'],
                    'time' => $row['time'],
                    'author' => tili::getUserByID($row['author']),
                    'thread' => keskustelu::getTopic($row['thread'])
                ));
            }
        }
        return $viestit;
    }
    
    public function getThread() {
        return $this->$thread;
    }

    public static function getByAuthor($id) {
        $query = DB::connection()->prepare('SELECT * FROM message where author = :id order by time desc LIMIT 10');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $viestit = array();
        foreach ($rows as $row) {
            $viestit[] = new viesti(array(
                'id' => $row['id'],
                'content' => $row['content'],
                'time' => $row['time'],
                'author' => tili::getUserByID($row['author']),
                'thread' => keskustelu::getTopic($row['thread'])
            ));
        }
        return $viestit;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO message (content, time, author, thread) VALUES (:content, :time, :author, :thread) RETURNING id');
        $query->execute(array('content' => $this->content, 'time' => $this->time, 'author' => $this->author, 'thread' => $this->thread));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE message SET content = :content where id= :id;');
        $query->execute(array('id' => $this->id, 'content' => $this->content));
        $row = $query->fetch();
    }

    public function delete() {
        $query = DB::connection()->prepare('DELETE from message where id=:id');
        $query->execute(array('id' => $this->id));
        $row = $query->fetch();
    }

    public function validateContent() {
        $errors[] = array();
        if (!BaseModel::validateLength($this->content, 5, 5000)) {
            $errors[] = 'Viestin tulee olla ainakin 5 merkkiä pitkä ja korkeintaan 5000';
        }
        return $errors;
    }

//    public function validateThread() {
//        $errors[] = array();
//        if (!ctype_digit($this->thread) || $this->thread < 0) {
//            $errors[] = 'Lankaa johon viesti yritetään lisätä ei ole olemassa!';
//        }
//        return $errors;
//    }
}
