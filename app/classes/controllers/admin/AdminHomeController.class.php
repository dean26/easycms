<?php



class AdminHomeController extends Controller
{

    public function getIndex($request, $response, $args)
    {
        return $this->slim->view->make('admin.home.index')->render();
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

    public function getSlug($request, $response, $args)
    {
        return AppHelper::createSlug($request->getParam('text'));
    }
}
