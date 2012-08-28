<?php
/**
 * Example helper that could run before a controller action to restrict
 * access to logged in users
 * 
 * @author jonathan@madepeople.se 
 */
class AppHelper
{
    public static function requiresLogin()
    {
        die('Access denied!');
    }
    
    public static function addRouteInfo($controller)
    {
        $controller->routes = print_r(Rework::getRouter()->getRoutes(), true);
    }
}