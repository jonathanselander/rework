<?php

class Rework_Loader
{
    private $_baseDir;
    
    private function _loadControllers()
    {
        $controllerDir = $this->_baseDir . '/app/controllers/';
        foreach (glob("$controllerDir/*Controller.php") as $file) {
            require_once $file;
            $controllerName = basename(str_replace('.php', '', $file));
            $controllerObject = new $controllerName;
            Rework::route($controllerObject);
        }
    }
    
    public function initialize($baseDir = './')
    {
        spl_autoload_register('Rework_Loader::load');
        set_include_path($baseDir . '/app/helpers/'
                . PATH_SEPARATOR . get_include_path()
                );
        
        $this->_baseDir = $baseDir;
        $this->_loadControllers();
    }
    
    public static function load($name)
    {
        $name = str_replace('_', '/', $name) . '.php';
        require_once $name;
    }
}