<?php
/**
 * Parse request parameters and validate against type classes if set 
 */
class Rework_Request
{
    const REQUEST_GET = 'get';
    const REQUEST_POST = 'post';
    const REQUEST_PUT = 'put';
    const REQUEST_DELETE = 'delete';
    
    public static function getRequestMethods()
    {
        return array(
            self::REQUEST_GET,
            self::REQUEST_POST,
            self::REQUEST_PUT,
            self::REQUEST_DELETE,
        );
    }
}