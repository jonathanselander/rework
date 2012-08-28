<?php
/**
 * Parameter validation interface
 * 
 * @author jonathan@madepeople.se
 */
interface Rework_Parameter_Interface
{
    /**
     * If the parameter doesn't validate we return a... 404? or perhaps 500?
     * 
     * @param string $parameter 
     */
    public static function validate($parameter);
}