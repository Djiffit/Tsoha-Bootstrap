<?php

class tili extends BaseModel {

    public $id, $name, $password;

    public function __construct($attributes) {
        parent::__construct($attributes);

        $this->validators = array('validateName', 'validatePassword');
    }

    public function all() {
        $query = DB::connection()->prepare('SELECT * from Loggedin');
        $query->execute();
        $rows = $query->fetchAll();
        $tilit = array();

        foreach ($rows as $row) {
            $tilit[] = new tili(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'password' => $row['password']
            ));
        }
        return $tilit;
    }

    public static function getUserByID($id) {
        $query = DB::connection()->prepare('SELECT * from Loggedin where id= :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        return $row['name'];
    }

    public static function getAccountByID($id) {
        $query = DB::connection()->prepare('SELECT * from loggedin where id=:id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        $tili = new tili(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'password' => $row['password']
        ));
        return $tili;
    }

    public static function getUserByName($name) {
        $query = DB::connection()->prepare('SELECT * from Loggedin where name= :name');
        $query->execute(array('name' => $name));
        $row = $query->fetch();

        $tili = new tili(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'password' => $row['password']
        ));
        return $tili;
    }

    public function validateName() {
        $errors[] = array();
        if (!BaseModel::validateLength($this->name, 5, 30)) {
            $errors[] = 'Nimen pitää olla yli 5 merkkiä ja alle 30 merkkiä!';
        }
        $rows = tili::all();
        foreach ($rows as $tili) {
            if ($this->name == $tili->name) {
                $errors[] = 'Nimi on jo käytössä! Nimen täytyy olla uniikki.';
            }
        }

        if (!ctype_alnum($this->name)) {
            $errors[] = 'Nimi saa sisältää vain numeroita ja kirjaimia!';
        }
        return $errors;
    }

    public function validatePassword() {
        $errors[] = array();
        if (!BaseModel::validateLength($this->password, 5, 30)) {
            $errors[] = 'Salasanan pitää olla yli 5 merkkiä ja alle 30 merkkiä!';
        }
        $rows = tili::all();
        if (!ctype_alnum($this->password)) {
            $errors[] = 'Salasana saa sisältää vain numeroita ja kirjaimia!';
        }
        return $errors;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Loggedin (name, password) VALUES (:namee, :epassword) returning id');
        $query->execute(array('namee' => $this->name, 'epassword' => $this->password));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function autentikoi($tunnus, $salasana) {
        $query = DB::connection()->prepare('SELECT * from Loggedin where name = :tunnus AND password = :salasana');
        $query->execute(array('tunnus' => $tunnus, 'salasana' => $salasana));
        $row = $query->fetch();
        if ($row) {
            $tili = new tili(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'password' => $row['password']
            ));
            return $tili;
        } else {
            return NULL;
        }
    }

}
