<?php

class Suosikki extends BaseModel {

    public $threadid, $userid;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function getSuosikitID($id) {
        $query = DB::connection()->prepare('SELECT * from Favorite where userid = :id');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $suosikit[] = array();
        foreach ($rows as $row) {
            $keskustelut = Keskustelu::keskusteluIdAvulla($row['threadid']);
            foreach ($keskustelut as $keskustelu) {
                if (count($keskustelu) > 0) {
                    $suosikit[] = $keskustelu;
                }
            }
        }
        $suosikit = array_filter($suosikit);
        return $suosikit;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Favorite (userid, threadid) VALUES (:userid, :threadid)');
        $query->execute(array('threadid' => $this->threadid, 'userid' => $this->userid));
    }

    public function delete() {
        $query = DB::connection()->prepare('DELETE from Favorite where threadid = :threadid and userid = :userid');
        $query->execute(array('userid' => $this->userid, 'threadid' => $this->threadid));
    }
    
    public function isSuosikki($user, $thread) {
        $query = DB::connection()->prepare('SELECT * from Favorite where threadid = :threadid and userid = :userid');
        $query->execute(array('userid' => $user, 'threadid' => $thread));
        $row = $query->fetch();
        if($row['userid'] > 0) {
            return true;
        } else {
            return false;
        }
    }

}
