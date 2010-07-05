<?php

class Controller_Index {
    protected $html;

    public function index() {
        $this->html = new Html('New ticket');
        $this->html->_js(
                'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js',
                'idtabs.js'
        );

        $this->html->_css('form.css', 'common.css', 'input.css');

        $sync = new Ticket_Sync;
        $sync_div = Tag::div($sync->form)->id('sync');
        $test_div = Tag::div('test')->id('sql');

        $idtab = Tag::div(
                $this->menu(Config_Core::$menu),
                Tag::div($sync_div, $test_div)->class('boxes')
        )->class('idTabs');
        
        //if($_POST) echo $sync->save();
        echo $this->html->_body($idtab);
    }

    private function menu($links)
    {
        $ul = Tag::ul();
        foreach($links AS $code => $label)
        {
            $ul->_add(Tag::li(Tag::a($label)->href('#' . $code)));
        }

        return $ul->class('menu');
    }

    public function load($args) {
        $sync = Storer::load('Ticket_Sync', $args[0]);
        $this->html = new Html('Load Ticket');
        $this->html->_css('form.css', 'common.css', 'input.css');
        echo $this->html->_body($sync->form);
    }

    public function testview() {
        View::$path = 'view/';

        $view = View::factory('form.css')
                ->bind('server', $account);

        $account = 'sarlanga';

        echo $view;
        exit;

        $view = View::factory('form.css');
        $view->server = $_SERVER;
        echo $view;
    }

    public function testsvn() {
        $svn = new Svn('http://svn.olx.com.ar:8080/repos/site/trunk');
        $log = $svn->log();
        print_r($log);

        foreach ($log->logentry as $logentry) {
            list($revision) = $logentry->attributes();
            //$out[$revision]->
        }

    }

    public function ex_test() {

        Tag::label('Tagname')->for('tag');
        Tag::input()->value('martín clasen es \"ñoño"')->name('q');
        Tag::textarea('testing </textarea>');

        Tag::select(
                Tag::option('hols')->id('style1'),
                Tag::option('chau')
        );

        Tag::button('hols')->type('submit');

        $select = Tag::select();
        $select->_add(
                Tag::option('hols'),
                Tag::option('chau')
        );

        $select->_add(Tag::option('last')->style('background-color:red'), Tag::option('lastone'));

        $tags = Tag::_get();
        echo Tag::form()->_set($tags);

        exit;



        Tag::br();

        var_dump(Tag::_get());

        $this->body(Tag::_get());

        exit;

        $st = new Ticket_Query;
        $st->insert();
        //echo $st->insert();

        print_r($st->all());
    }

    public function form() {
        $select = Tag::select();
        $select->_add(
                Tag::option('hols'),
                Tag::option('chau')
        );

        echo $select->_add(Tag::option('last')->style('background-color:red'), Tag::option('lastone'));

        $list[0] = new stdClass;
        $list[0]->name = 'item 1';
        $list[0]->id = '1';

        $list[1] = new stdClass;
        $list[1]->name = 'item 2';
        $list[1]->id = '2';

        $select = Tag::select()->name('test');

        foreach($list as $item)
            $select->_add( Tag::option($item->name)->value($item->id)->selected(2==$item->id) );

        echo $select->name('martin');
        $select->attr->name = '<tu vieja>';
        echo $select;

    }

    public function test()
    {
        echo  __FILE__;
    }
}