<?php

use Intervention\Image\ImageManagerStatic as Image;

class AdminGaleriaController extends Controller
{


    public function getIndex($req, $res, $args)
    {
        $data = $req->getParams();

        $orm = $this->slim->Model->getObj('Galeria');

        $warunki = [];
        $lista = [];

        if(@$data['tytul']) $warunki['AND']['tytul[~]'] = $data['tytul'];

        $lista["total"] = $orm->count($warunki);

        $strona = $req->getParam('strona', 1);

        $galeria = new Paginator(25, 'strona');
        $galeria->set_total($lista["total"]);

        $warunki["LIMIT"] = array(($strona - 1) * $galeria->get_perpage(), $galeria->get_perpage());

        if(@$data['sort_by'] && @$data['sort_d']){
            $warunki["ORDER"] =  ["galeria.".$data['sort_by'] => $data['sort_d']];
        } else {
            $warunki["ORDER"] =  ["galeria.created_at" => "DESC"];
        }

        $lista["wyniki"] = $orm->select("*", $warunki);

        $lista["pag_params"] = '';
        $lista["pag_params_no_sort"] = '';

        if(count($data) > 0) {
            unset($data['form_submit']);
            unset($data['csrf_name']);
            unset($data['csrf_value']);
            unset($data['strona']);
            foreach($data as $k => $v){
                if($v) $lista["pag_params"] .= '&'.$k.'='.$v;
                if($k != "sort_by" && $k != "sort_d")  $lista["pag_params_no_sort"] .= '&'.$k.'='.$v;
            }
        }

        $lista["pagination"] = $galeria->page_links("?", $lista["pag_params"]);

        return $this->slim->view->make('admin.galeria.index',
            array('lista' => $lista, 'req' => $req))->render();
    }

    public function getDelete_many($req, $res, $args)
    {

        $ids = $req->getParam('obj');

        if (count($ids) > 0) {

            $orm = $this->slim->Model->getObj('Galeria');
            $warunki = [];
            $warunki = [ "id" => $ids];

            //najpierw usuwamy miniaturki
            $lista = $orm->select("*", $warunki);
            foreach($lista as $l){
                @unlink(AppHelper::PublicPatch()."/public/uploads/galeria/".$l->plik);
            }

            $orm->delete($warunki);
        }

        AppHelper::setFlash('good', 'OK!');
        return $res->withRedirect(AppHelper::UrlTo("/galeria"));


    }

    public function getNew($req, $res, $args)
    {

        $data = $req->getParams();
        $data['poziom'] = 2;

        $object = $this->slim->Model->getObj('Galeria');

        return $this->slim->view->make('admin.galeria.new',
            array('object' => $object, 'data' => $data, 'errors' => array()))->render();

    }

    public function getCreate($req, $res, $args)
    {

        $data = $req->getParams();

        $object = $this->slim->Model->getObj('Galeria');

        $validated = Validation::is_valid(array_merge($data,$req->getUploadedFiles()), array(
            'tytul'    => 'required|max_len,256|min_len,3',
            'meta_title'    => 'max_len,256|min_len,3',
            'meta_decription'       => 'max_len,256|min_len,3',
            'meta_keywords'       => 'max_len,256|min_len,3',
            'slug'         => 'required|alpha_dash|max_len,128',
            'plik' => 'extension,png;jpg|filesize,5 MB'
        ));

        if($validated === true) {

            $object->tytul = $data["tytul"];
            $object->slug = $data["slug"];
            $object->wstep = $data["wstep"];
            $object->tresc = $data["tresc"];
            $object->status = $data["status"];
            $object->meta_title = $data["meta_title"];
            $object->meta_description = $data["meta_description"];
            $object->meta_keywords = $data["meta_keywords"];

            $files = $req->getUploadedFiles();
            if (!empty($files['plik'])) {
                $file = $files['plik'];
                if ($file->getError() === UPLOAD_ERR_OK) {
                    $destinationPath = AppHelper::PublicPatch()."/public/uploads/galeria";

                    $extension_buf = explode(".", $file->getClientFilename());
                    $extension = strtolower($extension_buf[count($extension_buf) - 1]);

                    $filename = substr(\AppHelper::createSlug($file->getClientFilename()), 0, -3) . '.' . $extension;
                    if(file_exists($destinationPath . '/' . $filename)) $filename = uniqid(time()) . '.' . $extension;

                    $file->moveTo($destinationPath . '/' . $filename);

                    Image::make($destinationPath . '/' . $filename)->resize(150, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->crop(150, 100)->save();

                    $object->plik = $filename;
                }
            }

            $object->save();

            AppHelper::setFlash('good', 'OK!');

            if($data['zastosuj'] == 1){
                return $res->withRedirect(AppHelper::UrlTo("/galeria/edit?id=".$object->id));
            } else {
                return $res->withRedirect(AppHelper::UrlTo("/galeria"));
            }

        } else {
            AppHelper::setFlash('error', 'Popraw błędy w formularzu.');
            return $this->slim->view->make('admin.galeria.new',
                array('object' => $object, 'data' => $data, 'errors' => $validated))->render();
        }

    }

    public function getEdit($req, $res, $args)
    {

        $data = $req->getParams();

        $object = $this->slim->Model->getObj('Galeria');
        $object->loadData($data["id"]);

        return $this->slim->view->make('admin.galeria.new',
            array('object' => $object, 'data' => $data, 'errors' => array()))->render();

    }

    public function getUpdate($req, $res, $args)
    {

        $data = $req->getParams();

        $object = $this->slim->Model->getObj('Galeria');
        $object->loadData($data["id"]);

        $validated = Validation::is_valid(array_merge($data,$req->getUploadedFiles()), array(
            'tytul'    => 'required|max_len,256|min_len,3',
            'meta_title'    => 'max_len,256|min_len,3',
            'meta_decription'       => 'max_len,256|min_len,3',
            'meta_keywords'       => 'max_len,256|min_len,3',
            'slug'         => 'required|alpha_dash|max_len,128',
            'plik' => 'extension,png;jpg|filesize,5 MB'
        ));

        if($validated === true) {

            $object->tytul = $data["tytul"];
            $object->slug = $data["slug"];
            $object->wstep = $data["wstep"];
            $object->tresc = $data["tresc"];
            $object->status = $data["status"];
            $object->meta_title = $data["meta_title"];
            $object->meta_description = $data["meta_description"];
            $object->meta_keywords = $data["meta_keywords"];

            $files = $req->getUploadedFiles();
            if (!empty($files['plik'])) {
                $file = $files['plik'];
                if ($file->getError() === UPLOAD_ERR_OK) {
                    $destinationPath = AppHelper::PublicPatch()."/public/uploads/galeria";

                    $extension_buf = explode(".", $file->getClientFilename());
                    $extension = strtolower($extension_buf[count($extension_buf) - 1]);

                    $filename = substr(\AppHelper::createSlug($file->getClientFilename()), 0, -3) . '.' . $extension;
                    if(file_exists($destinationPath . '/' . $filename)) $filename = uniqid(time()) . '.' . $extension;

                    $file->moveTo($destinationPath . '/' . $filename);

                    Image::make($destinationPath . '/' . $filename)->resize(150, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->crop(150, 100)->save();

                    $object->plik = $filename;
                }
            }

            if(@$data['usun_plik'] == 1){
                @unlink(AppHelper::PublicPatch()."/public/uploads/galeria/".$object->plik);
                $object->plik = null;
            }

            $object->save();

            AppHelper::setFlash('good', 'OK!');

            if($data['zastosuj'] == 1){
                return $res->withRedirect(AppHelper::UrlTo("/galeria/edit?id=".$object->id));
            } else {
                return $res->withRedirect(AppHelper::UrlTo("/galeria"));
            }

        } else {
            AppHelper::setFlash('error', 'Popraw błędy w formularzu.');
            return $this->slim->view->make('admin.galeria.new',
                array('object' => $object, 'data' => $data, 'errors' => $validated))->render();
        }

    }


}
