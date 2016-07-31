<?php


class AdminPageController extends Controller
{


    public function getIndex($req, $res, $args)
    {
        $data = $req->getParams();

        $orm = $this->slim->Model->getObj('Page');

        $warunki = [];
        $lista = [];

        if(@$data['tytul']) $warunki['AND']['tytul[~]'] = $data['tytul'];

        $lista["total"] = $orm->count($warunki);

        $strona = $req->getParam('strona', 1);

        $pages = new Paginator(25, 'strona');
        $pages->set_total($lista["total"]);

        $warunki["LIMIT"] = array(($strona - 1) * $pages->get_perpage(), $pages->get_perpage());

        if(@$data['sort_by'] && @$data['sort_d']){
            $warunki["ORDER"] =  ["pages.".$data['sort_by'] => $data['sort_d']];
        } else {
            $warunki["ORDER"] =  ["pages.created_at" => "DESC"];
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

        return $this->slim->view->make('admin.page.index',
            array('lista' => $lista, 'req' => $req))->render();
    }

    public function getDelete_many($req, $res, $args)
    {

        $ids = $req->getParam('obj');

        if (count($ids) > 0) {

            $orm = $this->slim->Model->getObj('Page');
            $warunki = [];
            $warunki = [ "id" => $ids];
            $orm->delete($warunki);
        }

        AppHelper::setFlash('good', 'OK!');
        return $res->withRedirect(AppHelper::UrlTo("/page"));


    }

    public function getNew($req, $res, $args)
    {

        $data = $req->getParams();

        $object = $this->slim->Model->getObj('Page');

        return $this->slim->view->make('admin.page.new',
            array('object' => $object, 'data' => $data, 'errors' => array()))->render();

    }

    public function getCreate($req, $res, $args)
    {

        $data = $req->getParams();

        $object = $this->slim->Model->getObj('Page');

        $validated = Validation::is_valid($data, array(
            'tytul'    => 'required|max_len,256|min_len,3',
            'meta_title'    => 'max_len,256|min_len,3',
            'meta_decription'       => 'max_len,256|min_len,3',
            'meta_keywords'       => 'max_len,256|min_len,3',
            'slug'         => 'required|alpha_dash|max_len,128'
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

            $object->save();

            AppHelper::setFlash('good', 'OK!');

            if($data['zastosuj'] == 1){
                return $res->withRedirect(AppHelper::UrlTo("/page/edit?id=".$object->id));
            } else {
                return $res->withRedirect(AppHelper::UrlTo("/page"));
            }

        } else {
            AppHelper::setFlash('error', 'Popraw błędy w formularzu.');
            return $this->slim->view->make('admin.page.new',
                array('object' => $object, 'data' => $data, 'errors' => $validated))->render();
        }

    }

    public function getEdit($req, $res, $args)
    {

        $data = $req->getParams();

        $object = $this->slim->Model->getObj('Page');
        $object->loadData($data["id"]);

        return $this->slim->view->make('admin.page.new',
            array('object' => $object, 'data' => $data, 'errors' => array()))->render();

    }

    public function getUpdate($req, $res, $args)
    {

        $data = $req->getParams();

        $object = $this->slim->Model->getObj('Page');
        $object->loadData($data["id"]);

        $validated = Validation::is_valid(array_merge($data,$req->getUploadedFiles()), array(
            'tytul'    => 'required|max_len,256|min_len,3',
            'meta_title'    => 'max_len,256|min_len,3',
            'meta_decription'       => 'max_len,256|min_len,3',
            'meta_keywords'       => 'max_len,256|min_len,3',
            'slug'         => 'required|alpha_dash|max_len,128'
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

            $object->save();

            AppHelper::setFlash('good', 'OK!');

            if($data['zastosuj'] == 1){
                return $res->withRedirect(AppHelper::UrlTo("/page/edit?id=".$object->id));
            } else {
                return $res->withRedirect(AppHelper::UrlTo("/page"));
            }

        } else {
            AppHelper::setFlash('error', 'Popraw błędy w formularzu.');
            return $this->slim->view->make('admin.page.new',
                array('object' => $object, 'data' => $data, 'errors' => $validated))->render();
        }

    }


}
