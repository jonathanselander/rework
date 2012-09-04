<?php
/**
 * Basic view renderer class for different formats determined by annotation
 * 
 * @author jonathan@madepeople.se 
 */
class Rework_View
{
    const METHOD_HTML = 'html';
    const METHOD_HTML_LAYOUT = 'html_layout';
    const METHOD_PLAIN = 'plain';
    const METHOD_JSON = 'json';
    
    private $_content;

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
            case self::METHOD_HTML_LAYOUT:
                // Extracted parameters are nice in a template file
                extract($parameters, EXTR_PREFIX_SAME, 'tpl_');
                ob_start();
                include 'app/views/' . $data . '.phtml';
                $content = ob_get_contents();
                ob_end_clean();

                if ($method !== self::METHOD_HTML_LAYOUT) {
                    $layoutConfig = Rework::getConfig('layout');
                    if (!empty($layoutConfig)) {
                        // Load view inside a layout
                        $layout = new Rework_View;
                        $layoutPath = '../layouts/' . $layoutConfig;
                        $parameters = array_merge(
                                array('_content' => $content),
                                $parameters
                            );
                        
                        $content = $layout->render($layoutPath,
                                $parameters,
                                self::METHOD_HTML_LAYOUT);
                    }
                }

                return $content;
            case self::METHOD_PLAIN:
                return $data;
            case self::METHOD_JSON:
                return json_encode($data);
        }
        
        throw new Exception('Invalid view render method "' . $method . "'");
    }
}