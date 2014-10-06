<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
//use Date,Time, AppHelper, Mail;

// Models
use app\models\Daystats;
use app\models\Hoursstats;
use app\models\Client;
use app\models\Emails;
use app\models\Job;


class EmailReportCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'EmailReport:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Emails Reports Stats';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the email command.
	 *
	 * @return mixed
	 */
	public function fire(){

        $schedule = $this->argument('schedule');

        if ( $schedule=="DAY" ) {
            $schedule='1 Day';
        }elseif($schedule=="WEEK") {
            $schedule='7 Day';
        }else{
            $schedule='31 Day';
        }

        $emails=Emails::where('when', '=', $schedule)->get();

        foreach ($emails as $email)
        {
            $tjobs = Job::where('jobstatus','=', 'T')
                      ->where('starttime',  '>=',  Date::now()->sub($schedule))
                      ->where('endtime',    '<=',  Date::now())
                      ->whereIn('clientid', unserialize($email->clients))
                      ->get();
                      $data['table'] = $tjobs;
            /* sends Email */
            Mail::send('emails.report', $data, function($message)
            {
                $message->to($email->emails)
                      ->subject('Bacukps Stats '.$sechedule.' Report');
            });
        }

	}

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('schedule', InputArgument::REQUIRED, 'Required, DAY, WEEK or MONTH'),
        );
    }


}
