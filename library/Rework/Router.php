<?php
/**
 * Class for building the routing table used for dispatching controller
 * actions correctly
 * 
 * @author jonathan@madepeople.se 
 */
class Rework_Router
{
    /**
     * Standard, automagically constructed routes
     * @var array
     */
    private $_routeOriginals = array();
    
    /**
     * Overridden routes on action level
     * @var array
     */
    private $_routeProvides = array();
    
    /**
     * Final routing table
     * @var array
     */
    private $_routes = array();
    
    /**
     * Reflect controllers and their actions and build the routes
     * 
     * @param Rework_Controller $controller
     * @return \Rework_Router 
     */
    public function addRoute(Rework_Controller $controller)
    {
        $reflector = new Rework_Reflection;
        $controllerData = $reflector->reflect($controller);

        $baseName = strtolower(preg_replace('/Controller$/', '',
                get_class($controller)));

        $requestMethods = Rework_Request::getRequestMethods();
        foreach ($controllerData as $action => $settings) {
            $routedAction = '';
            foreach ($requestMethods as $method) {
                if (preg_match("/^{$method}[A-Z][a-z]+$/", $action)) {
                    $routedAction = preg_replace("/^$method/", '', $action);
                    break;
                }
            }
            
            if (empty($routedAction)) {
                // Non-routable method
                continue;
            }
            
            $routedAction = strtolower($routedAction);
            
            // Let's hope PHP uses the same object for all actions
            $settings['controller'] = $controller;
            $settings['action'] = $action;

            $route = '/' . $baseName . '/' . $routedAction;
            if (!empty($settings[Rework_Reflection::ANNOTATION_ROUTE])) {
                $settings['_originalRoute'] = $route;
                $route = $settings[Rework_Reflection::ANNOTATION_ROUTE];
                $this->_routeProvides[$route] = $settings;
            } else {
                $this->_routeOriginals[$route] = $settings;
            }
        }

        // Make sure the route order is correct
        $this->aggregateRoutes();
        
        return $this;
    }
    
    /**
     * Route table getter
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->_routes;
    }
    
    /**
     * Make sure routes are stored in the right order
     * 
     * @return \Rework_Router 
     */
    public function aggregateRoutes()
    {
        $this->_routes = array_replace($this->_routeOriginals,
                $this->_routeProvides);
        
        return $this;
    }
    
    /**
     * Expects a URI cleaned from request variables and other junk
     * 
     * @param string $uri
     */
    public function match($uri)
    {
        // TODO: Handle parameters
        return isset($this->_routes[$uri])
                ? $this->_routes[$uri]
                : false;
    }
}