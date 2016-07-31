<?php

class User extends Model {

    protected $db_name = "users";

    public static $typy = [1 => "SuperAdmin", 2 => "Admin"];
    public static $status = [1 => "Aktywny", 2 => "Nieaktywny"];

    public function __construct()
    {

    }
}

?>