<?php
class Router {

    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION = 'index';

    public static function run() {

        $url = $_SERVER['REQUEST_URI'];

        $route = array('controller' => Router::DEFAULT_CONTROLLER, 'action' => Router::DEFAULT_ACTION);

        $url = explode('/', $url);
        $url = array_filter($url);

        if (count($url) > 1) {
            $route['controller'] = $url[1];
            $route['action'] = $url[2];
        } else if (count($url) > 0) $route['controller'] = $url[1];
		

        $controllerClass = ucfirst(strtolower($route['controller'])) . 'Controller';
        $controllerPath = APPLICATION_PATH . '/controllers/' . $controllerClass . '.php';

        if (!is_file($controllerPath)) throw new Exception("No such controller under path " . $controllerPath);

        require $controllerPath;

        if (!class_exists($controllerClass)) throw new Exception("No class has been found!");
		

        $controllerObject = new $controllerClass();
        $actionName = $route['action'] . 'Action';
		

        if (!method_exists($controllerObject, $actionName)) throw new Exception("No action!");

        Model::connect();
        $controllerObject->$actionName();
    }
}