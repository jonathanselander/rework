<?php
/**
 * Basic autoloader that loads all controller files for convenience
 * 
 * @author jonathan@madepeople.se 
 */
class Rework_Loader
{
    /**
     * Holds the project basedir
     * @var string
     */
    private $_baseDir;
    
    /**
     * Require and set up routes for all controllers
     * 
     * @return \Rework_Loader
     */
    private function _loadControllers()
    {
        $controllerDir = $this->_baseDir . '/app/controllers/';
        foreach (glob("$controllerDir/*Controller.php") as $file) {
            require_once $file;
            $controllerName = basename(str_replace('.php', '', $file));
            $controllerObject = new $controllerName;
            Rework::route($controllerObject);
        }
        
        return $this;
    }
    
    /**
     * Initialize autoloader
     * 
     * @param string $baseDir
     * @return \Rework_Loader 
     */
    public function initialize($baseDir = './')
    {
        spl_autoload_register('Rework_Loader::load');
        set_include_path($baseDir . '/app/helpers/'
                . PATH_SEPARATOR . get_include_path()
                );
        
        $this->_baseDir = $baseDir;
        $this->_loadControllers();
        
        return $this;
    }
    
    /**
     * Simple autoloader method
     * 
     * @param string $name
     */
    public static function load($name)
    {
        $name = str_replace('_', '/', $name) . '.php';
        require_once $name;
    }
}