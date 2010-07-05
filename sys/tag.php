<?php

class Tag {
    public static $charset = 'utf-8';
    public $name = '';
    public $attr;
    public $childs = array();

    protected function __construct($name, array $childs = array()) {
        $this->name = $name;
        $this->attr = new StdClass;
        $this->childs = $childs;
    }

    public function __call($attribute, $arg) {
        if( ! isset($arg[0])) {
            throw new Exception('Missing argument 1');
        } elseif($arg[0] === TRUE) {
            $this->attr->$attribute = $attribute;
        } elseif($arg[0] !== FALSE) {
            $this->attr->$attribute = $arg[0];
        }

        return $this;
    }

    public function _add() {
        $args = func_get_args();
        if(isset($args[0]) AND is_array($args[0])) $args = $args[0];

        $this->childs = array_merge($this->childs, $args);
        return $this;
    }

    public function __toString() {
        $html = "<". $this->name . self::vars($this->attr);
        if($this->childs) {
            $content = '';
            foreach($this->childs as $child)
            {
                if(is_array($child))
                    $content .= implode($child);
                else
                    $content .= is_object($child) ? $child : htmlspecialchars($child, ENT_QUOTES, self::$charset);
            }
            return $html .">". $content ."</". $this->name .">\n";
        }

        return $html ." />\n";
    }

    protected static function vars($attributes = NULL) {
        if (empty($attributes))
            return '';

        $compiled = '';
        foreach ($attributes as $key => $value) {
            if ($value === NULL) continue;

            // Add the attribute value
            $compiled .= ' '.$key.'="'.htmlspecialchars($value, ENT_QUOTES, self::$charset).'"';
        }

        return $compiled;
    }

    // PHP 5.3.0
    public static function __callStatic($tagName, $arg) {
        return new Tag($tagName, $arg);
    }
	
	// PHP < 5.2.x
	public static function input() 		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function label() 		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function textarea()	{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function select()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function option()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function br()			{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function form()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function button()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function h1()			{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function h2()			{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function h3()			{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function html()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function head()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function title()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function body()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function script()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function ul()			{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function li()			{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function checkbox()	{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function table()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
	public static function tr()			{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }		
	public static function td()			{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
    public static function th()			{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
    public static function link()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
    public static function thead()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
    public static function tbody()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
    public static function div()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
    public static function strong()		{ $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }
    public static function a()		    { $arg = func_get_args(); return self::__callStatic(__FUNCTION__, $arg); }

}