<?php

class Tili extends BaseModel {

    public $id, $name, $password, $moderator;

    public function __construct($attributes) {
        parent::__construct($attributes);

        $this->validators = array('validateName', 'validatePassword');
    }

    public function all() {
        $query = DB::connection()->prepare('SELECT * from Loggedin where id < 999 order by id');
        $query->execute();
        $rows = $query->fetchAll();
        $tilit = array();
        foreach ($rows as $row) {
            $tilit[] = new Tili(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'password' => $row['password'],
                'moderator' => $row['moderator']
            ));
        }
        return $tilit;
    }

    public static function getKayttajaIDlla($id) {
        $query = DB::connection()->prepare('SELECT * from Loggedin where id= :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        return $row['name'];
    }

    public static function getTiliIDlla($id) {
        $query = DB::connection()->prepare('SELECT * from loggedin where id=:id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        $tili = new Tili(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'password' => $row['password'],
            'moderator' => $row['moderator']
        ));
        return $tili;
    }

    public function validateName() {
        $errors[] = array();
        if (!BaseModel::validateLength($this->name, 5, 30)) {
            $errors[] = 'Nimen pitää olla yli 5 merkkiä ja alle 30 merkkiä!';
        }
        $rows = Tili::all();
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
        $rows = Tili::all();
        if (!ctype_alnum($this->password)) {
            $errors[] = 'Salasana saa sisältää vain numeroita ja kirjaimia!';
        }
        return $errors;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Loggedin (name, password, moderator) VALUES (:namee, :epassword, 0) returning id');
        $query->execute(array('namee' => $this->name, 'epassword' => $this->password));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function paivitaNimi($uusi) {
        $query = DB::connection()->prepare('UPDATE Loggedin SET name = :uusi where id= :id');
        $query->execute(array('id' => $this->id, 'uusi' => $uusi));
    }

    public function paivitaSalasana($uusi) {
        $query = DB::connection()->prepare('UPDATE Loggedin SET password = :uusi where id= :id');
        $query->execute(array('id' => $this->id, 'uusi' => $uusi));
    }

    public function delete() {
        $query = DB::connection()->prepare('DELETE from favorite where userid = :id');
        $query->execute(array('id' => $this->id));
        $query = DB::connection()->prepare('UPDATE message SET author = 999 where author = :id');
        $query->execute(array('id' => $this->id));
        $query = DB::connection()->prepare('UPDATE thread SET starter = 999 where starter = :id');
        $query->execute(array('id' => $this->id));
        $query = DB::connection()->prepare('DELETE from Loggedin where id = :id');
        $query->execute(array('id' => $this->id));
    }

    public function autentikoi($tunnus, $salasana) {
        $query = DB::connection()->prepare('SELECT * from Loggedin where name = :tunnus AND password = :salasana');
        $query->execute(array('tunnus' => $tunnus, 'salasana' => $salasana));
        $row = $query->fetch();
        if ($row) {
            $tili = new Tili(array(
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
