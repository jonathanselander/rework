<?php

class Rework_Controller
{
    public function __call($name, $arguments)
    {
        // TODO: handle _XXX for response codes, eg:
        //  $this->_200("wassup");
        
        // TODO: handle argument as body for response
        
        if (preg_match('/^_\d{3}$/', $name)) {
            $responseCode = str_replace('_', '', $name);
            http_response_code($responseCode);
        }
    }
}