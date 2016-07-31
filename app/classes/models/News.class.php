<?php

class News extends Model {

    protected $db_name = "news";

    public static $status = [1 => "Aktywny", 2 => "Nieaktywny"];
    public static $poziom = [1 => "1", 2 => "2", 3 => 3];

    public function __construct()
    {

    }
}

?>