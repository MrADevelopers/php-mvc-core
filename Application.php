<?php

namespace app\core;

use app\core\db\Database;
use app\core\db\DbModel;

class Application
{

    public static string $ROOT;
    public string $userClass;
    public string $layout = '_master';

    public Router $router;
    public Request $request;
    public Response $reponse;
    public ?Controller $controller = null;
    public SESSION $session;
    public View $view;
    public static Application $app;

    public Database $db;
    public ?UserModel $user;

    public function __construct($ROOT,array $config)
    {
         $this->userClass = $config['userClass'];

         self::$app = $this;
         self::$ROOT = $ROOT;
         $this->request = new Request ();
         $this->response = new Response ();
         $this->session = new Session();
         $this->router = new Router($this->request,$this->response);
         $this->view = new View();
         $this->db = new Database($config['db']);
        
         
         $primaryValue = $this->session->get('user');
         if($primaryValue)
         {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey=>$primaryValue]);

         }
         else
         {
             $this->user = null;
         }
         
    }

    public function getController()
    {
        return $this->$controller;
    }

    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }

    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user',$primaryValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest()
    {   
        return !(self::$app->user);
    }

    public function run()
    {
        try
        {
            echo $this->router->resolve();
        }
        catch(\Exception $e)
        {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error',[
                'exception'=>$e
            ]);
        }
        
    }


}


?>