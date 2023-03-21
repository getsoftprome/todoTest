<?php
namespace Core;
use Core\DB\Connect;
use Core\Helper\Common;

class App{
    private $router;
    private $rootPath;

    public function __construct($rootPath){
        $this->router = new Route();
        $this->rootPath = $rootPath;
    }

    public function start(){
        $this->router->add('MainController:mainPage','/');
        $this->router->add('AjaxController:ajax','/ajax');

        $route = $this->router->get(Common::getClenPathUrl($this->rootPath));
        if(!$route){
            $route = 'MainController:notFoundPage';
        }
        list($class, $action) = explode(':',$route, 2);

        $controller = 'Controller\\'.$class;

        call_user_func_array([new $controller(), $action],[$_GET]);
    }



}