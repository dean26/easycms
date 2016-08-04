<?php



class AdminHomeController extends Controller
{

    public function getIndex($req, $response, $args)
    {
        $data = $req->getParams();

        //pobranie wiadomosci
        $warunki = [];
        $lista = [];

        $lista["total"] = $this->slim->medoo->count("wiadomosci", $warunki);

        $strona = $req->getParam('strona', 1);

        $paginator = new Paginator(10, 'strona');
        $paginator->set_total($lista["total"]);

        $warunki["LIMIT"] = array(($strona - 1) * 10, 10);

        $warunki["ORDER"] =  ["wiadomosci.created_at" => "DESC"];

        $lista["wyniki"] = $this->slim->medoo->select("wiadomosci", "*", $warunki);

        $lista["pag_params"] = '';

        if(count($data) > 0) {
            unset($data['form_submit']);
            unset($data['csrf_name']);
            unset($data['csrf_value']);
            unset($data['strona']);
            foreach($data as $k => $v){
                if($v) $lista["pag_params"] .= '&'.$k.'='.$v;
            }
        }

        $lista["pagination"] = $paginator->page_links("?", $lista["pag_params"]);

        //staty
        if(!@$data['rok']) $data['rok'] = date('Y');
        if(!@$data['miesiac']) $data['miesiac'] = date('n');
        $data['ilosc_dni_w_mie'] = date('t', strtotime('01-'.$data['miesiac'].'-'.$data['rok']));
        $lista['staty'] = [];

        //odwiedziny
        $listaPre = $this->slim->medoo->select("staty",
            ['DAY(created_at) as dzien', 'COUNT(id) as ilosc'],
            [
             'YEAR(created_at)' => $data['rok'],
             'MONTH(created_at)' => $data['miesiac'],
             'ORDER' => 'DAY(created_at)',
             'GROUP' => 'DAY(created_at)'
            ]
        );

        $prefix = $this->slim->medoo->getPrefix();

        $listaPre = $this->slim->medoo->query(
        'SELECT DAY(created_at) as dzien, COUNT(id) as ilosc FROM '.$prefix.'staty
        WHERE YEAR(created_at) = '.$data['rok'].' AND MONTH(created_at) = '.$data['miesiac'].'
        GROUP BY DAY(created_at) ORDER BY DAY(created_at) ASC'
        )->fetchAll(PDO::FETCH_ASSOC);

        $listaPreFormat = [];

        foreach ($listaPre as $oo){
            $listaPreFormat[$oo['dzien']] = $oo['ilosc'];
        }

        for($i = 1; $i <= $data['ilosc_dni_w_mie']; $i++){
            $lista['staty'][$i] = (isset($listaPreFormat[$i])) ? $listaPreFormat[$i] : 0;
        }


        return $this->slim->view->make('admin.home.index', array('data' => $data, 'lista' => $lista))->render();
    }

    public function getStaty_ajax($req, $response, $args){

        $data = $req->getParams();

        $prefix = $this->slim->medoo->getPrefix();

        $lista = $this->slim->medoo->query('SELECT * FROM '.$prefix.'staty
        WHERE YEAR(created_at) = '.$data['rok'].' AND MONTH(created_at) = '.$data['miesiac'].' AND DAY(created_at) = '.$data['dzien'].'
        ORDER BY DAY(created_at) ASC'
        )->fetchAll(PDO::FETCH_ASSOC);


        return $this->slim->view->make('admin.home._staty_ajax', array('lista' => $lista))->render();
    }

    public static function getLastWia(){
        $lista = AppHelper::getMedooIns()->select("wiadomosci", "*", [
            "created_at[>]" => date('Y-m-d H:i:s', strtotime('-2 days')),
            "ORDER" => ["wiadomosci.created_at" => "DESC"],
            "LIMIT" => 15]);

        return AppHelper::getBladeIns()->make('admin.home._last_wia', array('lista' => $lista))->render();
    }

    public function getDelete_wia($req, $response, $args)
    {
        $data = $req->getParams();
        $this->slim->medoo->delete("wiadomosci", ["id" => (int)$data["id"]]);

        AppHelper::setFlash('good', 'OK!');
        return $response->withRedirect(AppHelper::UrlTo("/"));
    }

    public function getKontakt_form($req, $response, $args)
    {
        $data = $req->getParams();

        $data['nadawca'] = Auth::getConfig('admin_email');

        return $this->slim->view->make('admin.home.kontakt',
            array('data' => $data, 'errors' => array()))->render();
    }

    public function getKontakt($req, $response, $args)
    {
        $data = $req->getParams();

        $validated = Validation::is_valid(array_merge($data,$req->getUploadedFiles()), array(
            'temat'    => 'required|max_len,512|min_len,3',
            'odbiorca'    => 'required|max_len,64|min_len,3|valid_email',
            'nadawca'    => 'required|max_len,64|min_len,3|valid_email',
            'plik' => 'filesize,5 MB'
        ));

        if($validated === true) {

            $files = $req->getUploadedFiles();

            $zalacznik = null;

            if (!empty($files['plik'])) {
                $file = $files['plik'];
                if ($file->getError() === UPLOAD_ERR_OK) {
                    $destinationPath = AppHelper::PublicPatch()."/public/uploads/zalaczniki";

                    $extension_buf = explode(".", $file->getClientFilename());
                    $extension = strtolower($extension_buf[count($extension_buf) - 1]);

                    $filename = substr(\AppHelper::createSlug($file->getClientFilename()), 0, -3) . '.' . $extension;
                    if(file_exists($destinationPath . '/' . $filename)) $filename = uniqid(time()) . '.' . $extension;

                    $file->moveTo($destinationPath . '/' . $filename);

                    $zalacznik = $destinationPath . '/' . $filename;
                }
            }

            if(Auth::getConfig('mail_typ') == 2){

                $transport = Swift_MailTransport::newInstance();

            } else {

                $transport = Swift_SmtpTransport::newInstance(Auth::getConfig('smtp_host'), Auth::getConfig('smtp_port'))
                    ->setUsername(Auth::getConfig('smtp_login'))
                    ->setPassword(Auth::getConfig('smtp_haslo'));

            }

            $mailer = Swift_Mailer::newInstance($transport);

            $message = Swift_Message::newInstance()
                ->setSubject($data['temat'])
                ->addCc($data['nadawca'])
                ->setFrom(Auth::getConfig('admin_email'))
                ->setTo(array($data['odbiorca'] => 'Odbiorca wiadomości'))
                ->setBody($data['tresc'], 'text/html');

            if ($zalacznik) {
                $message->attach(Swift_Attachment::fromPath($zalacznik));
            }

            if ($mailer->send($message))
            {
                AppHelper::setFlash('good', 'Wiadomość została wysłana!');
            } else {
                AppHelper::setFlash('error', 'Wiadomość NIE została wysłana! Sprawdź log systemu.');
            }


            if ($zalacznik) {
                @unlink($zalacznik);
            }

            AppHelper::setFlash('good', 'Wiadomość została wysłana!');

            return $response->withRedirect(AppHelper::UrlTo("/"));


        } else {

            AppHelper::setFlash('error', 'Popraw błędy w formularzu.');

            return $this->slim->view->make('admin.home.kontakt',
                array('data' => $data, 'errors' => $validated))->render();

        }
    }

    public function getInfo($request, $response, $args)
    {
        $orm = $this->slim->Model->getObj('User');
        return $this->slim->view->make('admin.home.info', array('orm' => $orm))->render();
    }

    public function getError($request, $response, $args)
    {
        if($request->getParam('code') == '404'){
            return $this->slim->view->make('admin.home.error404')->render();
        } elseif($request->getParam('code') == '500'){
            return $this->slim->view->make('admin.home.error500')->render();
        } elseif($request->getParam('code') == 'csrf'){
            $this->slim->logger->addWarning("Błąd CSRF", array(
                'form' => $_SERVER['HTTP_REFERER'],
                'ip' => $_SERVER['REMOTE_ADDR']
            ));
            return $this->slim->view->make('admin.home.errorcsrf')->render();
        } else{
            return $this->slim->view->make('admin.home.error404')->render();
        }

    }

    public function getLogout($request, $response, $args)
    {

        $this->slim->logger->addInfo("Wylogowano użytkownika z panelu administracyjnego.", array(
            'admin' => Auth::getAdmin()->login." (".Auth::getAdmin()->imie." ".Auth::getAdmin()->nazwisko.")", 'ip' => $_SERVER['REMOTE_ADDR']
        ));

        Auth::logout();
        AppHelper::setFlash('good', 'Wylogowałeś się poprawnie.');
        return $response->withRedirect(AppHelper::BaseUrl(true).'login');
    }

    public function getLogin($request, $response, $args)
    {
        $data = array();
        $data['csrf_name'] = $request->getAttribute('csrf_name');
        $data['csrf_value'] = $request->getAttribute('csrf_value');
        return $this->slim->view->make('admin.login', array('data' => $data))->render();
    }

    public function getLogin_check($request, $response, $args)
    {
        $login = $request->getParam('login');
        $haslo = $request->getParam('haslo');

        if($login && $haslo){

            $admin = $this->slim->medoo->get("users", "*", ['AND' => ['login' => $login, 'status' => 1]]);

            if(!$admin){
                AppHelper::setFlash('error', 'Nie ma takiego użytkownika lub konto zostało zablokowane.');
            } else {
                if(time() > (int)$admin['blokada']){
                    if (password_verify($haslo, $admin['haslo'])) {
                        AppHelper::setFlash('good', 'Zalogowałeś się poprawnie.');
                        Auth::login($admin['id']);
                        $this->slim->medoo->update("users", ['blokada' => 0, 'zle_logowania_ilosc' => 0], ['id' => $admin['id']]);

                        $this->slim->logger->addInfo("Zalogowano użytkownika do panelu administracyjnego.", array(
                            'admin' => $login." (".$admin["imie"]." ".$admin["nazwisko"].")", 'ip' => $_SERVER['REMOTE_ADDR']
                        ));

                        return $response->withRedirect(AppHelper::BaseUrl(true));
                    } else {
                        AppHelper::setFlash('error', 'Hasło jest nieprawidłowe.');
                        $this->slim->medoo->update("users", ['zle_logowania_ilosc' => ($admin['zle_logowania_ilosc'] + 1)], ['id' => $admin['id']]);
                        if($admin['zle_logowania_ilosc'] + 1 >= 3){
                            $this->medoo->update("users", ['blokada' => strtotime('+10 minutes')], ['id' => $admin['id']]);
                        }

                        $this->slim->logger->addWarning("Użytkownik podał złe hasło do panelu administracyjnego.", array(
                            'admin' => $login." (".$admin["imie"]." ".$admin["nazwisko"].")", 'haslo' => $haslo, 'ip' => $_SERVER['REMOTE_ADDR']
                        ));
                    }
                } else {
                    AppHelper::setFlash('error', ' Zablokowana możliwość logowania do godziny '.date('H:i', $admin['blokada']).'');
                }
            }

        } else {
            AppHelper::setFlash('error', 'Musisz podać login i hasło.');
        }

        $data = array();
        $data['csrf_name'] = $request->getAttribute('csrf_name');
        $data['csrf_value'] = $request->getAttribute('csrf_value');
        return $this->slim->view->make('admin.login', array('data' => $data))->render();
    }

    public function getLogi($request, $response, $args)
    {

        $pliki = [];
        if ($handle = opendir(AppHelper::PublicPatch() ."/app/logs")) {

            while (false !== ($entry = readdir($handle))) {
                if($entry != "." && $entry != "..")
                $pliki[] = str_replace(array("_", ".app.log"), array("/", ""), $entry);
            }

            closedir($handle);
        }

        rsort($pliki);

        $linie = $request->getParam('linie', 100);
        $plik_key = $request->getParam('plik', 0);

        $filename = str_replace("/", "_", $pliki[$plik_key]).".app.log";

        $lista = AppHelper::ReadLogs(AppHelper::PublicPatch() ."/app/logs/".$filename, $linie);
        return $this->slim->view->make('admin.home.logi', array('lista' => $lista, 'req' => $request, 'pliki' => $pliki))->render();
    }

    public function getConfig($request, $response, $args)
    {
        $data = $this->slim->medoo->select("opcje", "*");
        $config = [];

        foreach($data as $k => $v){
            $config[$v["nazwa"]] = $v["wartosc"];
        }

        return $this->slim->view->make('admin.home.opcje', array('config' => $config))->render();
    }

    public function getConfig_edit($request, $response, $args)
    {
        $data = $request->getParams();

        foreach($data as $k => $v){
            $this->slim->medoo->update("opcje", ["wartosc" => $v], ["nazwa" => $k]);
        }

        AppHelper::setFlash('good', 'OK!');

        return $response->withRedirect(AppHelper::UrlTo("/home/config"));
    }

    public function getSlug($request, $response, $args)
    {
        return AppHelper::createSlug($request->getParam('text'));
    }
}
