<?php


class AdminMenuController extends Controller
{

    public function getIndex($req, $res, $args)
    {
        $data = $req->getParams();

        $errors = [];
        $object_menu = $this->slim->Model->getObj('Menu');

        if ((int)@$data["id"] > 0) $object_menu->loadData((int)$data["id"]);

        if ((int)@$data['from_form'] == 1) {

            $validated = Validation::is_valid($data, array(
                'tytul' => 'required|max_len,128',
                'link' => 'required|max_len,256'
            ));

            if ($validated === true) {

                $object_menu->tytul = $data["tytul"];
                $object_menu->link = $data["link"];
                $object_menu->status = $data["status"];
                $object_menu->pozycja = $data["pozycja"];
                $object_menu->target = $data["target"];
                $object_menu->parent_id = $data["parent_id"];
                if ((int)@$data["id"] == 0) $object_menu->kolejnosc = 0;
                $object_menu->save();

                AppHelper::setFlash('good', 'OK!');
                return $res->withRedirect(AppHelper::UrlTo('/menu'));

            } else {
                $errors = $validated;
                AppHelper::setFlash('error', 'Popraw błędy w formularzu.');
            }

        }

        $orm = $this->slim->Model->getObj('Menu');

        $warunki = [];
        $lista = [];

        $lista["total"] = $orm->count($warunki);

        $strona = $req->getParam('strona', 1);

        $warunki["ORDER"] =  ["menus.kolejnosc" => "ASC"];
        $warunki['AND']["parent_id"] =  0;

        foreach(Menu::$pozycja as $k => $v){
            $warunki['AND']['pozycja'] = $k;
            $lista["wyniki"][$k] = $orm->select("*", $warunki);
        }

        return $this->slim->view->make('admin.menu.index',
            array('lista' => $lista, 'req' => $req, 'data' => $data,
                'errors' => $errors, 'object_menu' => $object_menu))->render();
    }

    public function getDelete_many($req, $res, $args)
    {

        $ids = $req->getParam('obj');

        if (count($ids) > 0) {

            $orm = $this->slim->Model->getObj('Menu');
            $warunki = [];
            $warunki = [ "id" => $ids];

            //najpierw usuwamy miniaturki
            $lista = $orm->select("*", $warunki);
            foreach($lista as $l){
                @unlink(AppHelper::PublicPatch()."/public/uploads/menu/".$l->plik);
            }

            $orm->delete($warunki);
        }

        AppHelper::setFlash('good', 'OK!');
        return $res->withRedirect(AppHelper::UrlTo("/menu"));


    }

    public function getKolejnosc($req, $res, $args)
    {
        $data = $req->getParams();

        $licznik = 1;
        $kolej = explode(",", $data['kolej']);

        foreach($kolej as $k => $v){
            $buf = explode("_", $v);
            if((int)@$buf[1] > 0){
                $this->slim->medoo->update("menus", ['kolejnosc' => $licznik], ['id' => $buf[1]]);
                $licznik++;
            }
        }

        return "OK";

    }

}
