<?php

class Ticket extends Storer
{
	public $form;
	
	public function __construct()
	{
        parent::__construct();
        $this->form = new Form($_POST);
        $info = date('r', time()) .' @ '. gethostbyaddr($_SERVER['REMOTE_ADDR']) .' ('. $_SERVER['REMOTE_ADDR'] .')';
        $this->form->_read_only('from', 'From', $info)
            ->_input('summary', 'Summary');
    }

	public function parent()
	{
		return strtolower(parent::parent() . DIRECTORY_SEPARATOR . __CLASS__);
	}
}