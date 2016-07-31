<?php

use Intervention\Image\ImageManagerStatic as Image;

class AdminUserController extends Controller
{


    public function getIndex($req, $res, $args)
    {
        $data = $req->getParams();

        $orm = $this->slim->Model->getObj('User');

        $warunki = [];
        $lista = [];

        if(@$data['login']) $warunki['AND']['login[~]'] = $data['login'];
        if(@$data['nazwisko']) $warunki['AND']['nazwisko[~]'] = $data['nazwisko'];

        $lista["total"] = $orm->count($warunki);

        $strona = $req->getParam('strona', 1);

        $pages = new Paginator(25, 'strona');
        $pages->set_total($lista["total"]);

        $warunki["LIMIT"] = array(($strona - 1) * $pages->get_perpage(), $pages->get_perpage());

        if(@$data['sort_by'] && @$data['sort_d']){
            $warunki["ORDER"] =  ["users.".$data['sort_by'] => $data['sort_d']];
        } else {
            $warunki["ORDER"] =  ["users.created_at" => "DESC"];
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

        $lista["pagination"] = $pages->page_links("?", $lista["pag_params"]);

        return $this->slim->view->make('admin.user.index',
            array('lista' => $lista, 'req' => $req))->render();
    }

    public function getDelete_many($req, $res, $args)
    {

        $ids = $req->getParam('obj');

        if (count($ids) > 0) {

            $orm = $this->slim->Model->getObj('User');
            $warunki = [];
            $warunki = [ "id" => $ids];
            $orm->delete($warunki);
        }

        AppHelper::setFlash('good', 'OK!');
        return $res->withRedirect(AppHelper::UrlTo("/user"));


    }

    public function getNew($req, $res, $args)
    {

        $data = $req->getParams();

        $object = $this->slim->Model->getObj('User');

        return $this->slim->view->make('admin.user.new',
            array('object' => $object, 'data' => $data, 'errors' => array()))->render();

    }

    public function getCreate($req, $res, $args)
    {

        $data = $req->getParams();

        $object = $this->slim->Model->getObj('User');

        $validated = Validation::is_valid($data, array(
            'login'    => 'required|alpha_numeric|max_len,32|min_len,3|uniq,'.$object->count(['login' => $data['login']]),
            'imie'    => 'required|max_len,32|min_len,3',
            'nazwisko'       => 'required|max_len,32|min_len,3',
            'email'       => 'required|max_len,64|min_len,3|valid_email',
            'haslo'         => 'required|compare,haslo2|max_len,32|min_len,3',
            'plik' => 'extension,png;jpg|filesize,5 MB'
        ));

        if($validated === true) {

            $object->login = $data["login"];
            $object->imie = $data["imie"];
            $object->nazwisko = $data["nazwisko"];
            $object->email = $data["email"];
            $object->typ = $data["typ"];
            $object->status = $data["status"];
            if(isset($data['haslo']) && strlen($data['haslo']) > 0) $object->haslo = password_hash($data['haslo'], PASSWORD_BCRYPT);

            $files = $req->getUploadedFiles();
            if (!empty($files['plik'])) {
                $file = $files['plik'];
                if ($file->getError() === UPLOAD_ERR_OK) {
                    $destinationPath = AppHelper::PublicPatch()."/public/uploads/users";

                    $extension_buf = explode(".", $file->getClientFilename());
                    $extension = strtolower($extension_buf[count($extension_buf) - 1]);

                    $filename = substr(\AppHelper::createSlug($file->getClientFilename()), 0, -3) . '.' . $extension;
                    if(file_exists($destinationPath . '/' . $filename)) $filename = uniqid(time()) . '.' . $extension;

                    $file->moveTo($destinationPath . '/' . $filename);

                    Image::make($destinationPath . '/' . $filename)->resize(50, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->crop(50, 50)->save();

                    $object->plik = $filename;
                }
            }

            $object->save();

            AppHelper::setFlash('good', 'OK!');

            if($data['zastosuj'] == 1){
                return $res->withRedirect(AppHelper::UrlTo("/user/edit?id=".$object->id));
            } else {
                return $res->withRedirect(AppHelper::UrlTo("/user"));
            }

        } else {
            AppHelper::setFlash('error', 'Popraw błędy w formularzu.');
            return $this->slim->view->make('admin.user.new',
                array('object' => $object, 'data' => $data, 'errors' => $validated))->render();
        }

    }

    public function getEdit($req, $res, $args)
    {

        $data = $req->getParams();

        $object = $this->slim->Model->getObj('User');
        $object->loadData($data["id"]);

        return $this->slim->view->make('admin.user.new',
            array('object' => $object, 'data' => $data, 'errors' => array()))->render();

    }

    public function getUpdate($req, $res, $args)
    {

        $data = $req->getParams();

        $object = $this->slim->Model->getObj('User');
        $object->loadData($data["id"]);

        if($data["login"] != $object->login){
            $uniq = $object->count(['login' => $data['login']]);
        } else {
            $uniq = 0;
        }

        $validated = Validation::is_valid(array_merge($data,$req->getUploadedFiles()), array(
            'login'    => 'required|alpha_numeric|max_len,32|min_len,3|uniq,'.$uniq,
            'imie'    => 'required|max_len,32|min_len,3',
            'nazwisko'       => 'required|max_len,32|min_len,3',
            'email'       => 'required|max_len,64|min_len,3|valid_email',
            'haslo'         => 'compare,haslo2|max_len,32|min_len,3',
            'plik' => 'extension,png;jpg|filesize,5 MB'
        ));

        if($validated === true) {

            $object->login = $data["login"];
            $object->imie = $data["imie"];
            $object->nazwisko = $data["nazwisko"];
            $object->email = $data["email"];
            $object->typ = $data["typ"];
            $object->status = $data["status"];
            if(isset($data['haslo']) && strlen($data['haslo']) > 0) $object->haslo = password_hash($data['haslo'], PASSWORD_BCRYPT);

            $files = $req->getUploadedFiles();
            if (!empty($files['plik'])) {
                $file = $files['plik'];
                if ($file->getError() === UPLOAD_ERR_OK) {
                    $destinationPath = AppHelper::PublicPatch()."/public/uploads/users";

                    $extension_buf = explode(".", $file->getClientFilename());
                    $extension = strtolower($extension_buf[count($extension_buf) - 1]);

                    $filename = substr(\AppHelper::createSlug($file->getClientFilename()), 0, -3) . '.' . $extension;
                    if(file_exists($destinationPath . '/' . $filename)) $filename = uniqid(time()) . '.' . $extension;

                    $file->moveTo($destinationPath . '/' . $filename);

                    Image::make($destinationPath . '/' . $filename)->resize(50, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->crop(50, 50)->save();

                    $object->plik = $filename;
                }
            }

            if(@$data['usun_plik'] == 1){
                unlink(AppHelper::PublicPatch()."/public/uploads/users/".$object->plik);
                $object->plik = null;
            }

            $object->save();

            AppHelper::setFlash('good', 'OK!');

            if($data['zastosuj'] == 1){
                return $res->withRedirect(AppHelper::UrlTo("/user/edit?id=".$object->id));
            } else {
                return $res->withRedirect(AppHelper::UrlTo("/user"));
            }

        } else {
            AppHelper::setFlash('error', 'Popraw błędy w formularzu.');
            return $this->slim->view->make('admin.user.new',
                array('object' => $object, 'data' => $data, 'errors' => $validated))->render();
        }

    }


}
