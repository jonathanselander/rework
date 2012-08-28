<?php
/**
 * Controller class that implements functionality for simple HTTP response
 * management and view rendering
 * 
 * @author jonathan@madepeople.se
 * 
 * @method void _200()
 * @method void _201()
 * @method void _203()
 * @method void _204()
 * @method void _205()
 * @method void _206()
 * @method void _300()
 * @method void _301()
 * @method void _302()
 * @method void _303()
 * @method void _304()
 * @method void _305()
 * @method void _306()
 * @method void _307()
 * @method void _400()
 * @method void _401()
 * @method void _402()
 * @method void _403()
 * @method void _404()
 * @method void _405()
 * @method void _406()
 * @method void _407()
 * @method void _408()
 * @method void _409()
 * @method void _410()
 * @method void _411()
 * @method void _412()
 * @method void _413()
 * @method void _414()
 * @method void _415()
 * @method void _416()
 * @method void _417()
 * @method void _418()
 * @method void _500()
 * @method void _501()
 * @method void _502()
 * @method void _503()
 * @method void _504()
 * @method void _505() 
 */
class Rework_Controller
{
    /**
     * When set, overrides the view default
     * 
     * @var string
     */
    private $_renderMethod;
    
    /**
     * Use magic to handle custom response codes
     * 
     * @param string $name
     * @param array $arguments
     * @throws \BadMethodCallException
     */
    public final function __call($name, $arguments)
    {
        // TODO: handle argument as body for response
        
        if (preg_match('/^_\d{3}$/', $name)) {
            $responseCode = str_replace('_', '', $name);
            $this->_renderAndRespond($arguments[0], $arguments[1],
                    $responseCode);
            return;
        }

        throw new BadMethodCallException("The method '$name' does not exist");
    }
    
    /**
     * Render the view as phtml, plaintext or json, set the response code
     * and respond
     * 
     * @param string $argument
     * @param string $format 
     * @param int $responseCode
     */
    protected final function _renderAndRespond($argument,
            $method = null,
            $responseCode = 200)
    {
        if ($method === null) {
            // If the method is null we check if it's been set using
            // an annotation
            $method = $this->getRenderMethod();
        }
        
        $view = new Rework_View;
        $body = $view->render($argument, get_object_vars($this), $method);
        $response = Rework::getResponse();
        $response->setResponseCode($responseCode)
                ->setBody($body)
                ->send();
    }

    /**
     * Short-hand for 200
     * 
     * @param string $argument
     */
    protected final function ok($argument, $method = null)
    {
        $this->_200($argument, $method);
    }
    
    /**
     * Short-hand for 404
     * 
     * @param string $argument
     */
    protected final function notfound($argument, $method = null)
    {
        return $this->_404($argument, $method);
    }
    
    /**
     * Short-hand for 500
     * 
     * @param string $argument
     */
    protected final function error($argument, $method = null)
    {
        return $this->_500($argument, $method);
    }
    
    /**
     * Setter for the view render method
     * 
     * @param string $method
     * @return \Rework_Controller 
     */
    public final function setRenderMethod($method)
    {
        $this->_renderMethod = $method;
        return $this;
    }
    
    /**
     * Getter for the view render method
     * 
     * @return string
     */
    public final function getRenderMethod()
    {
        return $this->_renderMethod;
    }
}