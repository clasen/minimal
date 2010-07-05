<?php

class Storer
{
    protected static $prefix = '_';
    protected static $ext = '.sc';
	public $id;
	private $_count;
	private $_name;

	public function __construct()
	{
		if( ! is_dir($this->name)) self::mkpath(Storer::path(get_class($this)));
	}

	public function __get($key)
	{
		if ($key == 'count')
		{
			return $this->attr($key, count( $this->all(false) ));
		} elseif ($key == 'name') {
			return $this->attr($key, strtolower( get_class($this) ));
		}
	}
	
	private function attr($var, $default)
	{
		$var = '_'. $var;
		if( ! $this->$var)
			$this->$var = $default;

		return $this->$var;
	}

	public function insert()
	{
		$file = $this->file();
		file_put_contents($file, serialize($this));
		$this->_count = NULL;
		return $file;
	}
	
	public function delete($id = NULL)
	{
		if($id === null) $id = $this->id;
		unlink($this->file($id));
	}
	
	public function update($id)
	{
		$file = $this->file($id);
		file_put_contents($file, serialize($this));
		return $file;		
	}
	
	public function all($reverse = TRUE)
	{
		$all = self::dir($this->parent());
		return $reverse ? array_reverse($all) : $all;
	}
	
	public function save()
	{
		if($this->id)
			return $this->update($this->id);
		else
			return $this->insert();
	}
	
	protected function parent()
	{
		return Storer::$prefix . __CLASS__;
	}
	
	protected function file($id = NULL)
	{
		if($id === NULL) $id = $this->count;
		return Storer::path(get_class($this)) . DIRECTORY_SEPARATOR . $id . Storer::$ext;
	}
	
	protected static function path($class_name)
	{
       $parts = explode('_', $class_name);

        $l = '';
        $r = Storer::$prefix . __CLASS__;
        foreach($parts as $p)
        {
            $r .= DIRECTORY_SEPARATOR . $l . $p;
            $l .= $p . '_';
        }

        return strtolower($r);
	}

	private static function mkpath($path)
	{
        if(file_exists($path) or mkdir($path)) return TRUE;
	    return (self::mkpath(dirname($path)) and mkdir($path));
	}
	
    public static function dir($path, &$list = NULL)
    {
    	if($list === null) $list = array();

		$d = dir($path);
    	while (false !== $file=$d->read())
    	{
    		if($file{0} != '.')
    		{
    			if(is_dir($d->path . DIRECTORY_SEPARATOR . $file)) 
    				self::dir($d->path . DIRECTORY_SEPARATOR . $file, $list);
    			else
    				$list[] = $d->path . DIRECTORY_SEPARATOR . $file;
    		}
    	}
    	$d->close();
    
    	return $list;
    }

	public static function load($class_name, $id)
	{
        $s = file_get_contents(Storer::path($class_name) . DIRECTORY_SEPARATOR . $id . Storer::$ext);
		return unserialize($s);
	}
	
}
