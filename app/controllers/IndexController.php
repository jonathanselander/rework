<?php
/**
 * Example IndexController skeleton, annotations can be used on the controllers
 * themselves, to encapsulate all actions
 * 
 * @author jonathan@madepeople.se
 * 
 * @before AppHelper::addRouteInfo
 */
class IndexController extends Rework_Controller
{
    /**
     * Receive GET
     * Call a method before the action runs
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
        $this->name = 'John Smith';
        $this->ok('index/index');
    }
    
    /**
     * Example route with parameters
     * 
     * Access with /index/test/foo/bar
     * @render plain
     */
    public function getTest($id, $name)
    {
        $this->ok('The passed ID is ' . $id . ', the name is ' . $name);
    }
}
