<?php

class Request
{
	public static $part;
	public static $controller;
	public static $action;
	public static $instance;

	public static function route($default_controller='index', $default_action='index')
	{
        self::$part = current($_GET) ? array() : explode('/', current(array_keys($_GET)));
		self::$controller = 'controller_'. ((isset(self::$part[0]) AND self::$part[0]) ? self::$part[0] : $default_controller);
		self::$instance = new self::$controller;		
		self::$action = $action = isset(self::$part[1]) ? self::$part[1] : $default_action;
		
		return self::$instance->$action(array_slice(self::$part, 2));
	}
}