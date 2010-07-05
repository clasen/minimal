<?php

class Sanitize
{
    public static $magic_quotes;

    public function  __construct() {
        Sanitize::set();
    }

    public static function set()
    {
        // Determine if the extremely evil magic quotes are enabled
		Sanitize::$magic_quotes = (bool) get_magic_quotes_gpc();

		// Sanitize all request variables
		$_GET    = Sanitize::clean($_GET);
		$_POST   = Sanitize::clean($_POST);
		$_COOKIE = Sanitize::clean($_COOKIE);
    }

	/**
	 * Recursively sanitizes an input variable:
	 *
	 * - Strips slashes if magic quotes are enabled
	 * - Normalizes all newlines to LF
	 *
	 * @param   mixed  any variable
	 * @return  mixed  sanitized variable
	 */
	public static function clean($value)
	{
		if (is_array($value) OR is_object($value))
		{
			foreach ($value as $key => $val)
			{
				// Recursively clean each value
				$value[$key] = Sanitize::clean($val);
			}
		}
		elseif (is_string($value))
		{
			if (Sanitize::$magic_quotes === TRUE)
			{
				// Remove slashes added by magic quotes
				$value = stripslashes($value);
			}

			if (strpos($value, "\r") !== FALSE)
			{
				// Standardize newlines
				$value = str_replace(array("\r\n", "\r"), "\n", $value);
			}
		}

		return $value;
	}
}