<?php
/**
 * HTTP response class
 *
 * @author jonathan@madepeople.se 
 */
class Rework_Response
{
    /**
     * HTTP status code map
     * @var array
     */
    private $_statusCodeMessages = array(
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported'
    );
    
    /**
     * Holds the body of the reponse
     * @var string
     */
    private $_body;

    public final function setResponseCode($code)
    {
        if (!isset($this->_statusCodeMessages[$code])) {
            throw new Exception('Incorrect HTTP status "' . $code . '"');
        }
        
        $message = $this->_statusCodeMessages[$code];
        header('HTTP/1.1 ' . $message, true, $code);
        
        return $this;
    }
    
    public final function setBody($body)
    {
        $this->_body = $body;
        return $this;
    }

    public final function send()
    {
        echo $this->_body;
        return $this;
    }
}