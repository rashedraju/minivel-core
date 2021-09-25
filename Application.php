<?php
namespace App\core;

use App\controllers\Controller;
use Exception;
use App\core\db\Database;

/**
 * @package App\core
 */
class Application{
    public static string $ROOT_DIR;
    public static Application $app;
    public string $layout = "main";
    public ?Controller $controller = null;
    public Request $request;
    public Router $router;
    public Response $response;
    public Database $database;
    public Session $session;
    public string $userClass;
    public ?UserModel $user = null;
    public View $view;

    function __construct(string $rootDir, array $config = [])
    {
        self::$ROOT_DIR = $rootDir;
        $this->userClass = $config['userClass'];
        self::$app = $this;
        $this->request = new Request;
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->database = new Database($config);
        $this->session = new Session();
        $this->view = new View();

        $this->loginBySession();
    }

    public function run(){
        try {
            echo $this->router->resolve();
        }catch (Exception $e){
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView("_error", [
                "exception" => $e
            ]);
        }
    }

    public function login(UserModel $user) : bool
    {
        $primaryKey = $user::getPrimaryKey();
        $primaryValue = $user->{$primaryKey};

        $this->session->set($primaryKey, $primaryValue);
        $this->user = $user;
        return true;
    }

    private function loginBySession()
    {
        $primaryKey = $this->userClass::getPrimaryKey();
        $primaryValue = $this->session->get($primaryKey);
        $user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        if($user){
            $this->user = $user;
        }else {
            $this->user = null;
        }
    }

    public function logout(){
        $this->user = null;
        $primaryKey = $this->userClass::getPrimaryKey();
        $this->session->remove($primaryKey);
    }

    public static function isGuest() : bool{
        return !self::$app->user;
    }
}