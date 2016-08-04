<?php

class Menu extends Model {

    protected $db_name = "menus";

    public static $status = [1 => "Aktywny", 2 => "Nieaktywny"];
    public static $pozycja = [1 => "Góra", 2 => "Dół"];
    public static $target = ["_self" => "Otwieraj w tym samym oknie", "_blank" => "Otwórz w nowej zakładce"];

    public function __construct()
    {

    }

    public function __toString(){
        return $this->tytul;
    }

    public function rodzice(){

        $warunki = [];
        $warunki['AND']["parent_id"] = [0];
        $warunki['ORDER'] = $this->db_name.".kolejnosc";
        if($this->id > 0) $warunki['AND']["id[!]"] = $this->id;
        if($this->pozycja > 0) $warunki['AND']["pozycja"] = $this->pozycja;

        $buf = $this->select(["id", "tytul"], $warunki);

        $lista = [];
        $lista[0] = "To jest pozycja nadrzędna";

        foreach($buf as $obj){
            $lista[$obj->id] = $obj->tytul;
        }

        return $lista;

    }

    public function dzieci($id = 0){

        $warunki = [];
        $warunki["parent_id"] = ($id > 0)? $id : $this->id;
        $warunki['ORDER'] = $this->db_name.".kolejnosc";

        $buf = $this->select(["id", "tytul"], $warunki);

        $lista = [];

        foreach($buf as $obj){
            $lista[$obj->id] = $obj->tytul;
        }

        return $lista;

    }

    public function zasoby(){

        $warunki = [];

        $buf = $this->_medoo->select("pages", ["id", "tytul", "slug"], ["ORDER" => "pages.tytul ASC", "status" => 1]);

        $lista = [];
        $lista["-"] = ["" => "Podłącz link do zasobu"];

        foreach($buf as $obj){
            $lista["Podstrony"]["/".$obj['slug']] = $obj['tytul'];
        }

        $buf = $this->_medoo->select("news", ["id", "tytul", "slug"], ["ORDER" => ["news.poziom" => "ASC", "news.created_at" => "DESC"], "status" => 1]);

        foreach($buf as $obj){
            $lista["Aktualności"]["/news/".$obj['slug']] = $obj['tytul'];
        }

        $buf = $this->_medoo->select("galeria", ["id", "tytul", "slug"], ["ORDER" => "galeria.tytul ASC", "status" => 1]);

        foreach($buf as $obj){
            $lista["Galeria"]["/galeria/".$obj['slug']] = $obj['tytul'];
        }

        return $lista;

    }
}

?>