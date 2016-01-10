<?php

class Keskustelualue extends BaseModel {

    public $name, $id, $description;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Subforum');
        $query->execute();
        $rows = $query->fetchAll();

        foreach ($rows as $row) {
            $alueet[] = new Keskustelualue(array(
                'name' => $row['name'],
                'id' => $row['id'],
                'description' => $row['description']
            ));
        }
        return $alueet;
    }
    
    public static function alifoorumiNimiIdlla($id) {
        $query = DB::connection()->prepare('SELECT name from subforum where id = :id');
        $query ->execute(array('id' =>$id));
        $nimi = $query->fetch();
        return $nimi['name'];
    }

    public static function alifoorumiIDlla($id) {
        $query = DB::connection()->prepare('SELECT * FROM Subforum where id= :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        $alueet[] = new Keskustelualue(array(
            'name' => $row['name'],
            'id' => $row['id'],
            'description' => $row['description']
        ));

        return $alueet;
    }

}
