<?php
/**
 * Use reflection to be able to create controller/action magic with 
 * annotation style definitions within comments 
 * 
 * @author jonathan@madepeople.se
 */
class Rework_Reflection
{
    const ANNOTATION_BEFORE = '@before';
    const ANNOTATION_AFTER = '@after';
    const ANNOTATION_ROUTE = '@route';
    const ANNOTATION_RENDER = '@render';
    
    /**
     * Parse comment parameters of a controller class for annotation
     * eg:
     *  @before Helper::requireLogin
     *  @format json
     *  @route custom/route
     * 
     * @param Rework_Controller|string $class
     * @return array
     * @throws Exception 
     */
    public function reflect($class)
    {
        $className = is_object($class)
                ? get_class($class)
                : $class;
        
        $reflectionData = array();
        $ref = new ReflectionClass($className);
        
        // First, reflect the class
        $lines = $this->_parseComment($ref->getDocComment());
        $classData = $this->_fetchAnnotationData($lines);
        
        // Then, reflect the methods
        foreach ($ref->getMethods() as $method) {
            $lines = $this->_parseComment($method->getDocComment());
            
            $methodName = $method->getName();
            
            $methodData = $this->_fetchAnnotationData($lines);
            $data = array_merge_recursive($classData, $methodData);
            $reflectionData[$methodName] = $data;
        }

        return $reflectionData;
    }
    
    /**
     * Split the comment string into multiple lines
     * 
     * @param string $comment
     * @return array
     */
    private function _parseComment($comment)
    {
        // http://stackoverflow.com/questions/1462720/iterate-over-each-line-in-a-string-in-php
        return preg_split("/((\r?\n)|(\r\n?))/", $comment);
    }

    /**
     * Parse a comment line into annotation
     * 
     * @param string $line
     * @return array|boolean
     * @throws Exception 
     */
    private function _parseCommentLine($line)
    {
        // http://stackoverflow.com/questions/2326125/remove-multiple-whitespaces-in-php
        $line = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $line);
        $line = str_replace('*', '', $line);
        $line = trim($line);

        $parts = explode(' ', $line);
        if (!preg_match('/^@/', $parts[0])) {
            // No annotation
            return false;
        }
        
        // TODO: This should actually only count if $parts[0] has an
        // annotation directive
//        if (count($parts) < 2) {
//            throw new Exception('Incorrect annotation for class: '
//                    . $className
//                    . ', method: ' . $methodName
//                    . ', annotation: ' . $parts[0]);
//        }
        
        return $parts;
    }
    
    /**
     * Parse annotation data from comments into an array
     * 
     * @param array $lines
     * @return array
     */
    private function _fetchAnnotationData($lines)
    {
        $data = array();
        foreach ($lines as $line) {
            $parts = $this->_parseCommentLine($line);
            if ($parts === false) {
                continue;
            }

            switch ($parts[0]) {
                case self::ANNOTATION_BEFORE:
                    if (empty($data[self::ANNOTATION_BEFORE])) {
                        $data[self::ANNOTATION_BEFORE] = array();
                    }
                    $data[self::ANNOTATION_BEFORE][] = $parts[1];
                    break;
                case self::ANNOTATION_AFTER:
                    if (empty($data[self::ANNOTATION_AFTER])) {
                        $data[self::ANNOTATION_AFTER] = array();
                    }
                    $data[self::ANNOTATION_AFTER][] = $parts[1];
                    break;
                case self::ANNOTATION_ROUTE:
                    $data[self::ANNOTATION_ROUTE] = $parts[1];
                    break;
                case self::ANNOTATION_RENDER:
                    $data[self::ANNOTATION_RENDER] = $parts[1];
                    break;
            }
        }
        return $data;
    }
}