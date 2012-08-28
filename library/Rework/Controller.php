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
     * One magic __call per request is all right
     * 
     * @param string $name
     * @param array $arguments
     * @throws \BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        // TODO: handle _XXX for response codes, eg:
        //  $this->_200("wassup");
        
        // TODO: handle argument as body for response
        
        if (preg_match('/^_\d{3}$/', $name)) {
            $responseCode = str_replace('_', '', $name);
            http_response_code($responseCode);
            return;
        }

        throw new BadMethodCallException("The method '$name' does not exist");
    }
}