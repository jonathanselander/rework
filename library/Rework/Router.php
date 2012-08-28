<?php

class Rework_Router
{
    private $_routeOriginals;
    private $_routeProvides;
    private $_routes;
    
    public function addRoute($controller)
    {
        $reflector = new Rework_Reflection;
        $controllerData = $reflector->reflect($controller);

        // TODO: implement queue system for route adding
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
            if (!empty($settings[Rework_Reflection::ANNOTATION_PROVIDES])) {
                $settings['_originalRoute'] = $route;
                $route = $settings[Rework_Reflection::ANNOTATION_PROVIDES];
                $this->_routeProvides[$route] = $settings;
            } else {
                $this->_routeOriginals[$route] = $settings;
            }
        }

        // Make sure route order is correct
        $this->aggregateRoutes();
        var_dump($this->_routes);
        return $this;
    }
    
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
        // TODO: Loop through all found routes and match against current request
        return isset($this->_routes[$uri])
                ? $this->_routes[$uri]
                : false;
    }
}