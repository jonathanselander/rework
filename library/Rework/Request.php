<?php
/**
 * Parse request parameters and validate against type classes if set 
 * 
 * @author jonathan@madepeople.se
 */
class Rework_Request
{
    const REQUEST_GET = 'get';
    const REQUEST_POST = 'post';
    const REQUEST_PUT = 'put';
    const REQUEST_DELETE = 'delete';
    
    /**
     * Holds the request parameters for the action
     * @var array
     */
    private $_params;
    
    /**
     * Return table of valid request methods
     * 
     * @return array
     */
    public static function getRequestMethods()
    {
        return array(
            self::REQUEST_GET,
            self::REQUEST_POST,
            self::REQUEST_PUT,
            self::REQUEST_DELETE,
        );
    }
    
    /**
     * Return a stripped down URI
     * 
     * @return string
     */
    public final function getUri()
    {
        return $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Returns the current request method
     * 
     * @return string
     */
    public final function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Get request parameters, with defaults depending on request method
     * 
     * @return array 
     */
    public final function getParams()
    {
        if (empty($this->_params)) {
            switch ($this->getMethod()) {
                case 'GET':
                    return $_GET;
                case 'POST':
                    return $_POST;
                default:
                    return $_REQUEST;
            }
        }
        return $this->_params;
    }
    
    /**
     * Set the request parameters to something custom
     * 
     * @param array $params
     * @return \Rework_Request
     * @throws Exception 
     */
    public final function setParams($params)
    {
        if (!is_array($params)) {
            throw new Exception('Request parameters has to be an associative array');
        }
        
        $this->_params = $params;
        return $this;
    }
}