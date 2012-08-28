<?php

class Rework
{
    private static $_router;
    private static $_loader;
    
    public static function getRouter()
    {
        if (is_null(self::$_router)) {
            self::$_router = new Rework_Router;
        }
        
        return self::$_router;
    }
    
    public static function route(Rework_Controller $controller)
    {
        $router = self::getRouter();
        $router->addRoute($controller);
    }
    
    public static function run($config = null)
    {
        require_once 'Rework/Loader.php';
        self::$_loader = new Rework_Loader;
        self::$_loader->initialize();
        self::dispatch();
    }
    
    public static function dispatch()
    {
        $router = self::getRouter();
        $match = $router->match($_SERVER['REQUEST_URI']); 
        if ($match !== false) {
            $controller = $match['controller'];
            $action = $match['action'];

            if (isset($match[Rework_Reflection::ANNOTATION_BEFORE])) {
                call_user_func($match[Rework_Reflection::ANNOTATION_BEFORE],
                        $controller);
            }
            
            $controller->$action();

            if (isset($match[Rework_Reflection::ANNOTATION_AFTER])) {
                call_user_func($match[Rework_Reflection::ANNOTATION_AFTER],
                        $controller);
            }
            
        } else {
            throw new Exception('404');
        }
    }
}