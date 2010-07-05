<?php

class Svn {
    public $repository = '';

    public function __construct($repository) {
        $this->repository = $repository;
    }

    public function log($args='--limit=5')
    {
        return $this->parse('log -v '.$args);
    }

    protected function parse($args)
    {
        static $logentry;

        $command = sprintf("svn $args --xml %s", $this->repository);

        if( ! isset($logentry[$command]))
        {
            $logentry[$command] = simplexml_load_string(shell_exec($command));
        }
        
        return $logentry[$command];
    }
}