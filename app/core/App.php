<?php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $controllerInput = isset($_GET['controller']) ? $_GET['controller'] : 'home';
        $controllerName = ucfirst($controllerInput) . 'Controller';

        // Check if controller exists
        if (file_exists('../app/controllers/' . $controllerName . '.php')) {
            $this->controller = $controllerName;
        } else {
             // Fallback or Error
             // If they try to access something that doesn't exist, send to Auth (Login) or specific 404
             // For now, default to AuthController which handles login
             $this->controller = 'HomeController';
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Check if method exists
        if (isset($_GET['action'])) {
            if (method_exists($this->controller, $_GET['action'])) {
                $this->method = $_GET['action'];
            }
        }

        // Get params - for now strictly GET query params are handled globally, 
        // but if we had URL segments they would go here.
        $this->params = [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }
}
