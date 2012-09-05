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
     * @render plain
     */
    public function getIndex()
    {
        $exception = $this->param('exception');
        $this->error('Caught "' . get_class($exception) . '": '
                . $exception->getMessage());
    }
    
    /**
     * Default 404 route
     * 
     * @render plain
     */
    public function getNotFound()
    {
        $this->notfound('404 Not Found');
    }
}
