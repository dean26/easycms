<?php
class Auth
{
    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */

    public static $logged_admin = null;
    public static $cms_configs = null;

    protected $slim;
    //Constructor
    public function __construct($ci) {
        $this->slim = $ci;
    }

    public function __invoke($request, $response, $next)
    {
        //tutaj sprawdzam czy admin zalogowany
        $data = $this->slim->medoo->select("opcje", "*");
        $config = [];

        foreach($data as $k => $v){
            $config[$v["nazwa"]] = $v["wartosc"];
        }

        self::$cms_configs = $config;

        if(AppHelper::getSesVar('zalogowany') == 1){
            //jesli tak to wykonuje dalej strone
            $record = $this->slim->medoo->get("users", "*", ['id' => AppHelper::getSesVar('user_id')]);

            $obj = new User();
            foreach($record as $k => $v){
                $obj->$k = $v;
            }

            self::$logged_admin = $obj;

            $response = $next($request, $response);
        } else {
            if($request->getUri()->getPath() != 'login' && $request->getUri()->getPath() != 'login_check'){
                AppHelper::setFlash('error', 'Musisz być zalogowany.');
            }
            return $response->withRedirect(AppHelper::BaseUrl(true).'home/login');
        }

        return $response;
    }

    public static function getAdmin(){
        return self::$logged_admin;
    }

    public static function getConfig($key){
        return (self::$cms_configs[$key])? self::$cms_configs[$key] : null;
    }

    public static function login($id){
        session_regenerate_id();
        AppHelper::setSesVar('zalogowany', 1);
        AppHelper::setSesVar('user_id', $id);
    }

    public static function logout(){
        AppHelper::DeleteAllSes();
    }
}