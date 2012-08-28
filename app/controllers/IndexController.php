<?php
/**
 * Example IndexController skeleton, annotations can be used on the controllers
 * themselves, to encapsulate all actions
 * 
 * @author jonathan@madepeople.se
 * 
 * @before AppHelper::displayRoutes
 */
class IndexController extends Rework_Controller
{
    /**
     * Receive GET
     * Call a method before the action runs
     *  DashboardController might send a routing exception
     * 
     * @before AppHelper::requiresLogin
     */
    public function getDashboard()
    {
        // Secret stuff can happen here
    }
    
    /**
     * Receive GET
     * Alias /index/index to /
     * 
     * @route /
     */
    public function getIndex()
    {
        echo 'Example!';
    }
}
