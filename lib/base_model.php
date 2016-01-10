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
        $errors = array();
        foreach ($this->validators as $validator) {
            $errors = array_merge($errors, $this->{$validator}());
            if (($this->{$validator}()) != NULL) {
                $errors = array_merge($errors, $this->{$validator}());
            }
        }
        $virheet = array();
        foreach ($errors as $error) {
            if (sizeof($error) >= 1 && !in_array($error, $virheet)) {
                array_push($virheet, $error);
            }
        }
        return $virheet;
    }

}
