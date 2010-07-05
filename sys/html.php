<?php

class Html extends Tag {
    public $head;
    public $body;
    public $js = array();
    public $css = array();

    public function  __construct($title='') {

        $this->head = Tag::head(Tag::title($title));
        $this->body = Tag::body();
        parent::__construct('html', array($this->head, $this->body));
    }

    public function _body()
    {
        $childs = func_get_args();
        $this->body->_add($childs);
        return $this;
    }

    public function _js()
    {
        $files = func_get_args();
        
        foreach($files AS $href)
        {
            $file = self::ensure_path($href, Config_Core::$path['js']);
            $this->head->_add(Tag::script('')->type('text/javascript')->src($file));
        }

        return $this;
    }

    public function _css()
    {
        $files = func_get_args();

        foreach($files AS $src)
        {
            $file = self::ensure_path($src, Config_Core::$path['css']);
            $this->head->_add(Tag::link()->type('text/css')->rel('stylesheet')->href($file));
        }

        return $this;
    }

    public function _script()
    {
        $lines = func_get_args();
        $this->head->_add(Tag::script($lines)->type('text/javascript'));
    }

    public function _head_open($code)
    {
        ob_start('head_script');
    }

    public function _head_close()
    {
        $add_head = ob_get_contents('head_script');
        $this->head->_add($add_head);
    }

    private static function ensure_path($uri, $path)
    {
        if(substr($uri,0,7) === 'http://')
            return $uri;

        return $path . $uri;
    }
}