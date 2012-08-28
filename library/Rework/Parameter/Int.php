<?php

class Rework_Parameter_Int implements Rework_Parameter_Interface
{
    public static function validate($parameter)
    {
        return is_int($parameter);
    }
}