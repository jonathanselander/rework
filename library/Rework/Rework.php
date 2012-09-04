<?php
/**
 * Main bootstrapper class
 * 
 * @author jonathan@madepeople.se 
 */
class Rework
{
    /**
     * Current router
     * @var \Rework_Router
     */
    private static $_router;
    
    /**
     * Current autoloader
     * @var \Rework_Loader
     */
    private static $_loader;
    
    /**
     * Current request
     * @var \Rework_Loader
     */
    private static $_request;
    
    /**
     * Current response
     * @var \Rework_Response
     */
    private static $_response;
    
    /**
     * Configuration data with default values
     * @var array
     */
    private static $_config = array(
        'layout' => 'default'
    );
    
    /** 
     * Current router getter
     * 
     * @return \Rework_Router
     */
    public static function getRouter()
    {
        if (is_null(self::$_router)) {
            self::$_router = new Rework_Router;
        }
        
        return self::$_router;
    }
    
    /**
     * Getter for current request
     * 
     * @return \Rework_Request
     */
    public static function getRequest()
    {
        return self::$_request;
    }
    
    /**
     * Getter for current response
     * 
     * @return \Rework_Response
     */
    public static function getResponse()
    {
        return self::$_response;
    }
    
    /**
     * Add controller route
     * 
     * @param Rework_Controller $controller 
     */
    public static function route(Rework_Controller $controller)
    {
        $router = self::getRouter();
        $router->addRoute($controller);
    }
    
    /**
     * Simple bootstrapper
     */
    public static function run($config = array())
    {
        require_once 'Rework/Loader.php';
        self::setConfig($config);
        self::$_loader = new Rework_Loader;
        self::$_loader->initialize();
        self::$_request = new Rework_Request;
        self::$_response = new Rework_Response;
        self::dispatch();
    }
    
    /**
     * Main dispatch function
     * 
     * @throws Exception 
     */
    public static function dispatch()
    {
        $router = self::getRouter();
        $request = self::getRequest();
        $match = $router->match($request);
        if ($match !== false) {
            $controller = $match['controller'];
            $action = $match['action'];

            if (!empty($match[Rework_Reflection::ANNOTATION_BEFORE])) {
                foreach ($match[Rework_Reflection::ANNOTATION_BEFORE] as $function) {
                    call_user_func($function, $controller);
                }
            }
            
            if (isset($match[Rework_Reflection::ANNOTATION_RENDER])) {
                $controller->setRenderMethod($match[Rework_Reflection::ANNOTATION_RENDER]);
            }
            
            call_user_func_array(array($controller, $action), 
                    $request->getParams());

            if (!empty($match[Rework_Reflection::ANNOTATION_AFTER])) {
                foreach ($match[Rework_Reflection::ANNOTATION_AFTER] as $function) {
                    call_user_func($function, $controller);
                }
            }
        } else {
            throw new Exception('404');
        }
    }
    
    /**
     * Setter for configuration value
     * 
     * @param array|string $data
     * @param mixed $value
     * @return void
     * @throws Exception 
     */
    public static function setConfig($data, $value = '')
    {
        if (is_array($data) && empty($value)) {
            $config = array_merge(self::$_config, $data);
            return;
        } else if (!is_array($data) && !empty($value)) {
            self::$_config[$data] = $value;
            return;
        }
        
        throw new Exception("Invalid configuration data");
    }
    
    /**
     * Return configuration value
     * 
     * @param string $key
     * @return mixed
     * @throws Exception 
     */
    public static function getConfig($key)
    {
        if (!isset(self::$_config[$key])) {
            throw new Exception("Config key '$key' isn't set");
        }
        
        return self::$_config[$key];
    }
}