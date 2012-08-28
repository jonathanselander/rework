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

            if (!empty($match[Rework_Reflection::ANNOTATION_BEFORE])) {
                foreach ($match[Rework_Reflection::ANNOTATION_BEFORE] as $function) {
                    call_user_func($function, $controller);
                }
            }
            
            $controller->$action();

            if (!empty($match[Rework_Reflection::ANNOTATION_AFTER])) {
                foreach ($match[Rework_Reflection::ANNOTATION_AFTER] as $function) {
                    call_user_func($function, $controller);
                }
            }
            
        } else {
            throw new Exception('404');
        }
    }
}