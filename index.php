<?php
/**
 * Basic bootstrapper
 * 
 * @author jonathan@madepeople.se 
 */
set_include_path(dirname(__FILE__) . '/library/'
        . PATH_SEPARATOR . get_include_path());

require_once 'Rework/Rework.php';

Rework::run();