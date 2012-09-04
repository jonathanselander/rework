<?php
/**
 * Example controller to handle route mismatches
 * 
 * @author jonathan@madepeople.se
 */
class ErrorController extends Rework_Controller
{
    /**
     * General error controller for exceptions caught within the application
     * 
     * @param Exception $exception 
     */
    public function getIndex()
    {
        $exception = $this->param('exception');
        $this->error('Caught "' . get_class($exception) . '": ' . $exception->getMessage(),
                Rework_View::METHOD_PLAIN);
    }
    
    /**
     * Default 404 route
     */
    public function getNotFound()
    {
        $this->notfound('404 Not Found', Rework_View::METHOD_PLAIN);
    }
}
