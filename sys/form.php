<?php

class Form extends Tag {

    public $table;
    public $row;
    public $values = array();
    public $option = array();

    public function  __construct($values = array(), $action='', $method='post') {
        $this->table = New Table();
        parent::__construct('form', array($this->table));
        $this->action($action)->method($method);
        $this->values = $values;
    }

    public function _append($name, $label, $input) {
        $row = new Stdclass;
        $row->label = Tag::label($label)->for($name);
        $row->input = $input;
        
        $tr = Tag::tr(Tag::th($row->label), Tag::td($row->input))->id('l_' . $name);
        $this->table->tbody->_add($tr);

        $this->row[$name] = $row;
        return $this;
    }

    public function _read_only($name, $label, $value) {
        $input = Tag::input($value)->type('hidden')->id($name)->name($name)->value($value);
        return $this->_append($name, $label, $input);
    }

    public function _input($name, $label, $value = FALSE) {
        if(isset($this->values[$name])) $value = $this->values[$name];

        $input = Tag::input()->type('text')->id($name)->name($name)->class(__FUNCTION__)->value($value);
        return $this->_append($name, $label, $input);
    }

    public function _select($name, $label, $options, $selected_list=array()) {
        if(isset($this->values[$name])) $selected_list = $this->values[$name];
        $this->option[$name] = $options;

        $select = Tag::select()->id($name)->name($name)->class(__FUNCTION__);

        foreach($options AS $value=>$label) {
            $select->_add(
                    Tag::option($label)->value($value)->selected(in_array($value, $selected_list))
            );
        }
        
        return $this->_append($name, $label, $select);
    }

    public function _checklist($name, $label, $options, $checked_list=array()) {
        if(isset($this->values[$name])) $checked_list = $this->values[$name];
        $this->option[$name] = $options;

        $ul = Tag::ul()->class(__FUNCTION__);

        foreach($options AS $value=>$optlabel) {
            $ul->_add(
                Tag::li(
                    Tag::input()->type('checkbox')->id('i'.$value)->name($name.'[]')
                        ->value($value)->checked(in_array($value, $checked_list)),
                    Tag::label($optlabel)->for('i'.$value)
                )
            );
        }
        return $this->_append($name, $label, $ul);
    }

    public function _submit($label)
    {
        $submit = Tag::button($label)->type('submit');
        $tr = Tag::tr(Tag::td(''), Tag::td($submit))->class(__FUNCTION__);
        $this->table->tbody->_add($tr);
    }
}