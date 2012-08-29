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
    private $_routeAliases = array();
    
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
            foreach ($requestMethods as $requestMethod) {
                if (preg_match("/^{$requestMethod}[A-Z][a-z]+$/", $action)) {
                    $routedAction = preg_replace("/^$requestMethod/", '', $action);
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
            $settings['requestMethod'] = $requestMethod;

            $parameterSuffix = '';
            if (count($settings['_parameters'])) {
                foreach ($settings['_parameters'] as $parameter) {
                    $parameterSuffix .= '/:' . $parameter->name;
                }
            }
            
            $route = '/' . $baseName . '/' . $routedAction . $parameterSuffix;
            if (!empty($settings[Rework_Reflection::ANNOTATION_ROUTE])) {
                $settings['_originalRoute'] = $route;
                $route = $settings[Rework_Reflection::ANNOTATION_ROUTE]
                        . $parameterSuffix;
                $this->_routeAliases[$route] = $settings;
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
                $this->_routeAliases);
        
        return $this;
    }
    
    /**
     * Expects a URI cleaned from request variables and other junk
     * 
     * @param string $uri
     */
    public function match(Rework_Request $request)
    {
        $uri = $request->getUri();
        
        // First, match the URI itself
        $match = null;
        foreach ($this->_routes as $route => $settings) {
            if ($uri === $route) {
                $match = $settings;
                break;
            }
            
            if ($request->getMethod() === 'GET' && count($settings['_parameters'])) {
                // Route has parameters, POST works differently
                $expression = $this->_convertRouteToRegex($route);
                preg_match_all($expression, $uri, $matches);
                
                // We don't want the match-of-all
                array_shift($matches);
                if (empty($matches[0])) {
                    // No match
                    continue;
                }
                
                $parameters = array();
                foreach ($settings['_parameters'] as $key => $parameter) {
                    $parameters[$parameter->name] = $matches[$key][0];
                }
                
                $match = $settings;
                $request->setParams($parameters);
            }
        }
        
        if ($match === null) {
            return false;
        }
        
        // Second, the request method
        if ($match['requestMethod'] !== strtolower($request->getMethod())) {
            return false;
        }
        
        return $match;
    }
    
    /**
     * Convert the defined route to a regular expression that can be used
     * to match against the requested URI
     * 
     * @param string $route
     * @return string 
     */
    private final function _convertRouteToRegex($route)
    {
        $patterns = array(
            '#/:[a-zA-Z_]+#',
        );
        
        $replacements = array(
            '/([^/]+)',
        );
        
        $expression = preg_replace($patterns, $replacements, $route);
        return "#$expression#";
    }
}