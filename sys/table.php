<?php

class Table extends Tag {

    public $thead;
    public $tbody;

    public function __construct() {
        parent::__construct('table');
        $args = func_get_args();
        //if(isset($args[0]) AND is_array($args[0])) $args = $args[0];

        if($args)
        {
            $tr = Tag::tr();
            foreach($args AS $arg)
                $tr->_add(Tag::th($arg));

            $this->thead = Tag::thead($tr);
            $this->_add($this->thead);
        }

        $this->tbody = Tag::tbody();
        $this->_add($this->tbody);
    }

    public function _tr() {
        $args = func_get_args();
        //if(isset($args[0]) AND is_array($args[0])) $args = $args[0];

        $tr = Tag::tr();
        foreach($args AS $arg)
            $tr->_add(Tag::td($arg));

        $this->tbody->_add($tr);

        return $this;
    }

}