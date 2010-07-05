<?php

class ErrorHandler
{
    public static $error = '';

    public function  __construct() {
        ErrorHandler::set();
    }

	public static function set() {
        set_error_handler(array('ErrorHandler', 'error_handler'));
        set_exception_handler(array('ErrorHandler', 'exception_handler'));
        register_shutdown_function(array('ErrorHandler', 'shutdown_handler'));
	}

    public static function exception_handler(Exception $e) {

        try
		{
			// Get the exception information
			$type    = get_class($e);
			$code    = $e->getCode();
			$message = $e->getMessage();
			$file    = $e->getFile();
			$line    = $e->getLine();

            $code = $code & error_reporting();
            if($code == 0) return;
            if(!defined('E_STRICT'))            define('E_STRICT', 2048);
            if(!defined('E_RECOVERABLE_ERROR')) define('E_RECOVERABLE_ERROR', 4096);
            switch($code){
                case E_ERROR:               $errcode = "Error";                  break;
                case E_WARNING:             $errcode = "Warning";                break;
                case E_PARSE:               $errcode = "Parse Error";            break;
                case E_NOTICE:              $errcode = "Notice";                 break;
                case E_CORE_ERROR:          $errcode = "Core Error";             break;
                case E_CORE_WARNING:        $errcode = "Core Warning";           break;
                case E_COMPILE_ERROR:       $errcode = "Compile Error";          break;
                case E_COMPILE_WARNING:     $errcode = "Compile Warning";        break;
                case E_USER_ERROR:          $errcode = "User Error";             break;
                case E_USER_WARNING:        $errcode = "User Warning";           break;
                case E_USER_NOTICE:         $errcode = "User Notice";            break;
                case E_STRICT:              $errcode = "Strict Notice";          break;
                case E_RECOVERABLE_ERROR:   $errcode = "Recoverable Error";      break;
                default:                    $errcode = "Unknown error $code";    break;
            }
            $note = array(Tag::strong($errcode), ': ', Tag::strong(strip_tags($message)), ' in ', Tag::strong($file), ' on line ', Tag::strong($line));
            $table = new Table();
            $table->class('grid')->_tr($note)
                ->tbody->childs[0]->style('color:white; background:red');

            $trace = new Table('i','call','file','line');
            $backtrace = $e->getTrace();
            array_shift($backtrace);
            foreach($backtrace as $i=>$l){
                if(isset($l['class'])) $trace->_tr($i, $l['class'] . $l['type'] . $l['function'], $l['file'], $l['line']);
            }
            
            $html = new Html();
            echo $html->_css('common.css')->_body($table->_tr($trace));
            if(isset($GLOBALS['error_fatal'])){
                if($GLOBALS['error_fatal'] & $code) die('fatal');
            }
        }
  		catch (Exception $e)
		{
			// Clean the output buffer if one exists
			ob_get_level() and ob_clean();

			// Display the exception text
			echo ErrorHandler::exception_text($e), "\n";

            // Exit with an error status
			exit(1);
		}

        return TRUE;
    }

	public static function error_handler($code, $error, $file = NULL, $line = NULL)
	{
		if (error_reporting() & $code)
		{
			// This error is not suppressed by current error reporting settings
			// Convert the error into an ErrorException
			throw new ErrorException($error, $code, 0, $file, $line);
		}

		// Do not execute the PHP error handler
		return TRUE;
	}

	public static function exception_text(Exception $e)
	{
		return sprintf('%s [ %s ]: %s ~ %s [ %d ]',
			get_class($e), $e->getCode(), strip_tags($e->getMessage()), $e->getFile(), $e->getLine());
	}

	public static function shutdown_handler()
	{
		if ($error = error_get_last())
		{
			// Clean the output buffer
			ob_get_level() and ob_clean();

			// Fake an exception for nice debugging
			echo ErrorHandler::exception_text(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));

            // Shutdown now to avoid a "death loop"
			exit(1);
		}
	}
}