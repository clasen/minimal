<?php

error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('America/Argentina/Buenos_Aires');
header("Content-type: text/html; charset=utf-8");


Autoload::$doc_root = realpath('');
Autoload::$load_path_priority = array('', '/sys');
spl_autoload_register('Autoload::register');
Autoload::$load_path_priority = Config_Autoload::$load_path_priority;
Autoload::$class_mapping = Config_Autoload::$class_mapping;


class Autoload
{
    public static $file_mask = '%s.php';
    public static $doc_root = '';

    // lower case classname => /path/to/class/file.php
    public static $class_mapping = array(
        //'controller_index' => '/lib/controller/class.index.php',
    );

    public static $load_path_priority = array('');

    public static function register($className)
    {
        $className = strtolower($className);
        
        if(isset(self::$class_mapping[$className]))
        {
            require self::$doc_root . self::$class_mapping[$className];
            return true;
        }

        foreach(self::$load_path_priority AS $path)
        {
            // Transform the class name into a path
            $fileName = '/'. str_replace('_', '/', sprintf(self::$file_mask, $className));
            $classFile = self::$doc_root . $path . $fileName;
            if(file_exists($classFile))
            {
                //self::$classMapping[$className] = $fileName;
                require $classFile;
                return true;
            }
        }

        return false;
    }
}
