<?php

use Intervention\Image\ImageManagerStatic as Image;

class AdminFotoController extends Controller
{


    public function getIndex($req, $res, $args)
    {
        $data = $req->getParams();

        $warunki = [];
        $warunki['AND']['ref_table'] = $data['ref'];
        $warunki['AND']['ref_id'] = $data['ref_id'];

        $ref_obj = $this->slim->Model->getObj(ucfirst($data['ref']));
        $ref_obj->loadData((int)$data["ref_id"]);

        if (!$ref_obj->id) {
            AppHelper::setFlash('error', 'Nie ma takiego obiektu!');
            return $res->withRedirect(AppHelper::getSesVar('prev_page', AppHelper::UrlTo("/")));
        }

        $orm = $this->slim->Model->getObj('Foto');
        $lista = [];
        $lista["total"] = $orm->count($warunki);

        $errors = [];
        $object_foto = $this->slim->Model->getObj('Foto');

        if ((int)@$data["id"] > 0) $object_foto->loadData((int)$data["id"]);

        if ((int)@$data['from_form'] == 1) {

            if (!$object_foto->id) {

                $validated = Validation::is_valid(array_merge($data, $req->getUploadedFiles()), array(
                    'opis' => 'max_len,1024'
                ));

            } else {

                $validated = Validation::is_valid(array_merge($data, $req->getUploadedFiles()), array(
                    'opis' => 'max_len,1024',
                    'plik' => 'extension,png;jpg|filesize,5 MB|required_file'
                ));

            }

            if ($validated === true) {

                $pliki = $req->getUploadedFiles();

                if ($object_foto->id > 0) {
                    $this->addObj($object_foto, $data, $pliki['plik']);
                } else {

                    foreach($pliki['plik'] as $k => $pl){
                        if ($pl->getError() === UPLOAD_ERR_OK){
                            $this->addObj($this->slim->Model->getObj('Foto'), $data, $pl, $lista["total"] + $k);
                        }

                    }

                }

                AppHelper::setFlash('good', 'OK!');

                return $res->withRedirect(AppHelper::UrlTo('/foto?form=1&ref_id=' . $data['ref_id'] . '&ref=' . $data['ref']));

            } else {
                $errors = $validated;
                AppHelper::setFlash('error', 'Popraw błędy w formularzu.');
            }

        }

        $strona = $req->getParam('strona', 1);

        $galeria = new Paginator(100, 'strona');
        $galeria->set_total($lista["total"]);

        $warunki["LIMIT"] = array(($strona - 1) * $galeria->get_perpage(), $galeria->get_perpage());

        $warunki["ORDER"] = ["fotos.kolejnosc" => "ASC"];

        $lista["wyniki"] = $orm->select("*", $warunki);

        $lista["pag_params"] = '';
        $lista["pag_params_no_sort"] = '';

        if (count($data) > 0) {
            unset($data['form_submit']);
            unset($data['csrf_name']);
            unset($data['csrf_value']);
            unset($data['strona']);
            foreach ($data as $k => $v) {
                if ($v) $lista["pag_params"] .= '&' . $k . '=' . $v;
                if ($k != "sort_by" && $k != "sort_d") $lista["pag_params_no_sort"] .= '&' . $k . '=' . $v;
            }
        }

        $lista["pagination"] = $galeria->page_links("?", $lista["pag_params"]);

        return $this->slim->view->make('admin.foto.index',
            array('lista' => $lista, 'req' => $req, 'object_foto' => $object_foto,
                'ref_obj' => $ref_obj, 'data' => $data, 'errors' => $errors))->render();
    }

    public function getDelete_many($req, $res, $args)
    {
        $data = $req->getParams();
        $ids = $req->getParam('obj');

        if (count($ids) > 0) {

            $orm = $this->slim->Model->getObj('Foto');
            $warunki = [];
            $warunki = ["id" => $ids];

            //najpierw usuwamy miniaturki
            $lista = $orm->select("*", $warunki);
            foreach ($lista as $l) {
                @unlink(AppHelper::PublicPatch() . "/public/uploads/galeria/" . $l->plik);
                @unlink(AppHelper::PublicPatch() . "/public/uploads/galeria/mini_" . $l->plik);
            }

            $orm->delete($warunki);
        }

        AppHelper::setFlash('good', 'OK!');
        return $res->withRedirect(AppHelper::UrlTo('/foto?form=1&ref_id=' . $data['ref_id'] . '&ref=' . $data['ref']));


    }

    public function addObj($obj, $data, $file, $kolejnosc)
    {
        $obj->opis = $data["opis"];
        $obj->kolejnosc = $kolejnosc;
        $obj->ref_table = $data["ref"];
        $obj->ref_id = $data["ref_id"];

        if($file){
            if ($file->getError() === UPLOAD_ERR_OK) {

                $destinationPath = AppHelper::PublicPatch() . "/public/uploads/galeria";

                $extension_buf = explode(".", $file->getClientFilename());
                $extension = strtolower($extension_buf[count($extension_buf) - 1]);

                $filename = substr(\AppHelper::createSlug($file->getClientFilename()), 0, -3) . '.' . $extension;
                if (file_exists($destinationPath . '/' . $filename)) $filename = uniqid(time()) . '.' . $extension;

                $file->moveTo($destinationPath . '/' . $filename);

                Image::make($destinationPath . '/' . $filename)->resize(150, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->crop(150, 100)->save($destinationPath . '/mini_' . $filename);

                $obj->plik = $filename;
            }
        }



        if (@$data['usun_plik'] == 1) {
            @unlink(AppHelper::PublicPatch() . "/public/uploads/galeria/mini_" . $obj->plik);
            @unlink(AppHelper::PublicPatch() . "/public/uploads/galeria/" . $obj->plik);
            $obj->plik = null;
        }

        $obj->save();
    }

    public function getKolejnosc($req, $res, $args)
    {
        $data = $req->getParams();

        $licznik = 1;
        $kolej = explode(",", $data['kolej']);

        foreach($kolej as $k => $v){
            $buf = explode("_", $v);
            $this->slim->medoo->update("fotos", ['kolejnosc' => $licznik], ['id' => $buf[1]]);
            $licznik++;
        }

        return "OK";

    }

}
