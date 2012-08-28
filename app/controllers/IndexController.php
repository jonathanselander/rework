<?php

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
     * @provides /
     */
    public function getIndex()
    {
        echo 'example!';        
    }
}