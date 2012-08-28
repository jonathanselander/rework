<?php
/**
 * Basic view renderer class for different formats determined by annotation
 * 
 * @author jonathan@madepeople.se 
 */
class Rework_View
{
    const METHOD_HTML = 'html';
    const METHOD_PLAIN = 'plain';
    const METHOD_JSON = 'json';

    /**
     * Render view using the given method
     * 
     * @param string $data
     * @param string $format
     * @return string
     * @throws Exception 
     */
    public final function render($data, $parameters = array(), $method = null)
    {
        if (empty($method)) {
            $method = self::METHOD_HTML;
        }
        
        switch ($method) {
            case self::METHOD_HTML:
                // Extracted parameters are nice in a template file
                extract($parameters, EXTR_PREFIX_SAME, 'tpl_');
                ob_start();
                include 'app/views/' . $data . '.phtml';
                $contents = ob_get_contents();
                ob_end_clean();
                return $contents;
            case self::METHOD_PLAIN:
                return $data;
            case self::METHOD_JSON:
                return json_encode($data);
        }
        
        throw new Exception('Invalid view render method "' . $method . "'");
    }
}