<?php

class Page extends Model {

    protected $db_name = "pages";

    public static $status = [1 => "Aktywna", 2 => "Nieaktywna"];

    public function __construct()
    {

    }

    public function __toString(){
        return $this->tytul;
    }
}

?>