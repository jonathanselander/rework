<?php

class Rework_Reflection
{
    const ANNOTATION_BEFORE = '@before';
    const ANNOTATION_AFTER = '@after';
    const ANNOTATION_PROVIDES = '@provides';
    const ANNOTATION_FORMAT = '@format';
    
    public function reflect($class)
    {
        // TODO: reflect classes with comment parameters for annotation
        // eg:
        //  @before Helper::requireLogin
        //  @format json
        //  @provides custom/route
        $className = get_class($class);
        $reflectionData = array();
        $ref = new ReflectionClass($className);
        foreach ($ref->getMethods() as $method) {
            $comment = $method->getDocComment();
            
            // http://stackoverflow.com/questions/1462720/iterate-over-each-line-in-a-string-in-php
            $lines = preg_split("/((\r?\n)|(\r\n?))/", $comment);
            $methodData = array();
            $methodName = $method->getName();
            
            foreach ($lines as $line) {
                // http://stackoverflow.com/questions/2326125/remove-multiple-whitespaces-in-php
                $line = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $line);
                $line = str_replace('*', '', $line);
                $line = trim($line);
                
                $parts = explode(' ', $line);
                if (!preg_match('/^@/', $parts[0])) {
                    // No annotation
                    continue;
                }
                if (count($parts) < 2) {
                    throw new Exception('Incorrect annotation for class: '
                            . $className
                            . ', method: ' . $methodName
                            . ', annotation: ' . $parts[0]);
                }

                switch ($parts[0]) {
                    case self::ANNOTATION_BEFORE:
                        $methodData[self::ANNOTATION_BEFORE] = $parts[1];
                        break;
                    case self::ANNOTATION_AFTER:
                        $methodData[self::ANNOTATION_AFTER] = $parts[1];
                        break;
                    case self::ANNOTATION_PROVIDES:
                        $methodData[self::ANNOTATION_PROVIDES] = $parts[1];
                        break;
                    case self::ANNOTATION_FORMAT:
                        $methodData[self::ANNOTATION_FORMAT] = $parts[1];
                        break;
                }
            }
            
            $reflectionData[$methodName] = $methodData;
        }

        return $reflectionData;
    }
}