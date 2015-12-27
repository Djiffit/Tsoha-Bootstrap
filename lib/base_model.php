<?php

class BaseModel {

    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    public function validateLength($string, $length1, $length2) {
        if (strlen($string) < $length1) {
            return false;
        }
        if (strlen($string) > $length2) {
            return false;
        }
        return true;
    }

    public function errors() {
        // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
        $errors[] = array();
        $virhe[] = array();

        foreach ($this->validators as $validator) {
            $metodi = $validator;
            $virhe[] = $this->{$metodi}();
            if (sizeof($virhe) > 0) {
                foreach ($virhe as $v) {
                    array_push($errors, $v);
                }
                
            }
            $virhe = null;
            $virhe = array();
            // Kutsu validointimetodia tässä ja lisää sen palauttamat virheet errors-taulukkoon
        }

        return $errors;
    }

}
