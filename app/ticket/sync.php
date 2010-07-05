<?php

class Ticket_Sync extends Ticket {
	
	public $modules = array('Site','Shared','Messages','Statics','Cron','Siteadmin','DAL','Billing');
	
	public function __construct()
	{
		parent::__construct();
        $this->form->class('form');
        $this->form->_checklist('module', 'Sync', $this->modules)
            ->_checklist('revision', 'Revision', $this->svnLog())
            ->_submit('Send');
	}

    public function svnLog($uri = 'http://svn.olx.com.ar:8080/repos/site/trunk')
    {
        $svn = new Svn($uri);
        $log = $svn->log();

        $svnlog = array();
        foreach ($log->logentry AS $logentry)
        {
            $rev = (int) $logentry['revision'];
            $svnlog[$rev] []= Tag::strong('R '. $rev .' | '. $logentry->msg);
            $svnlog[$rev] []= Tag::br() . $logentry->author . ' (' . self::datetime($logentry->date) . ')';

            foreach($logentry->paths->path AS $file)
            {
                $svnlog[$rev] []= Tag::br() . $file['action'] .' - '. $file[0];
            }

        }

        return $svnlog;
    }

    public static function datetime($strtime)
    {
        return date('H:s - d M Y', strtotime($strtime));
    }
}