<?php

namespace app\controllers;
use Auth, Controller, Form, Input, Redirect, Sentry;
use View, Log, Config, DB, Validator, Schema, Asset, Url;

use app\models\Group;
use app\models\User;


class InstallController extends Controller
{
    public function __construct()
    {

        Asset::add('bootstraptheme', 'assets/css/bootstrap-spacelab.css');
        Asset::add('bootstrapresponsive', 'assets/css/bootstrap-responsive.css');
        Asset::add('charisma', 'assets/css/charisma-app.css');
        Asset::add('uniform', 'assets/css/uniform.default.css');
        Asset::add('elfinder', 'assets/css/elfinder.min.css');
        Asset::add('opaicons', 'assets/css/opa-icons.css');
        Asset::add('famfam', 'assets/css/famfam.css');
        Asset::add('jqueryloadermin', 'assets/css/jquery.loader-min.css');
        Asset::add('reportula', 'assets/css/reportula.css');

        Asset::add('jquery', 'assets/js/jquery-2.0.3.min.js');
        Asset::add('eldarionform', 'assets/js/eldarion-ajax.min.js', 'jquery');

        Asset::add('jqueryloader', 'assets/js/jquery.loader-min.js', 'jquery');

        /* Get Monolog instance from Laravel */
        $monolog = Log::getMonolog();
        /* Add the FirePHP handler */
        $monolog->pushHandler(new \Monolog\Handler\FirePHPHandler());

    }

    /**
     * Display the login page
     * @return View
     */
    public function getInstall()
    {

        $driver   = Config::get('database.default', 'mysql');
        $database = Config::get('database.connections.'.$driver, array());
        $auth     = Config::get('auth');

        // for security, we shouldn't expose database connection to anyone.
        if (isset($database['password'])
            and ($password = strlen($database['password'])))
        {
            $database['password'] = str_repeat('*', $password);
        }

        $fluent_status = true;
        $eloquent_status = true;

        if ($auth['driver'] === 'fluent' and $auth['table'] !== 'users') {
            $fluent_status = false;
        }

        if ($auth['driver'] === 'eloquent') {
           // if (class_exists($auth['model'])) $driver = new $auth['model'];

         //   if ( ! (isset($driver) and $driver instanceof Orchestra\Model\User)) $eloquent_status = false;
        }

        $engine = array('MySQL','PostgresSQL'  );

        $data = array(
            'engine'          => $engine,
            'databasetype'    => $driver,
            'database'        => $database,
            'auth'            => $auth,
            'fluent_status'   => $fluent_status,
            'eloquent_status' => $eloquent_status,
        );

        return View::make('admin.auth.install',$data);
    }

    /**
     * TestDb Action
     * @return Sucess/Failed Html Code
     */
    public function testDb()
    {
        try {
            DB::connection(Config::get('database.default'))->getPdo();
            echo json_encode(array('html' => '<div class="alert alert-success"><i class="icon-fam-accept"></i> The Connection to the database was suscessfull  </div> '));
        } catch (Exception $e) {
              echo json_encode(array('html' => '<div class="alert alert-error"> <i class="icon-fam-cancel"></i> The Connection to the database was unuscessfull </div>') );
        }

    }

    /**
     * Sucess Action
     * @return Sucess Html Code
    */
    public function installSucess()
    {
        return View::make('admin.sucess');
    }

    public function installSave2()
    {
        echo json_encode(array('location' => 'install/installSucess'));
    }

    public function installSave()
    {

        try {
            //Getting our input from the Input library
            $input = Input::all();

            //Rules
            $rules = array(
               //'admin'     => 'required',
                'password'  => 'required',
                'email'     => 'required',
            );

            // Validate Rules Input Fields
            $validation = Validator::make($input, $rules);
            if ($validation->fails()) {
               return Redirect::to('install')->with_errors($validation);
            }


            if ( Schema::hasTable('users') == false ) {

                Schema::create('users', function ($table) {
                    $table->increments('id');
                    $table->string('email');
                    $table->string('password');
                    $table->text('permissions')->nullable();
                    $table->boolean('activated')->default(0);
                    $table->string('activation_code')->nullable();
                    $table->timestamp('activated_at')->nullable();
                    $table->timestamp('last_login')->nullable();
                    $table->string('persist_code')->nullable();
                    $table->string('reset_password_code')->nullable();
                    $table->string('first_name')->nullable();
                    $table->string('last_name')->nullable();
                    $table->text('remember_token')->nullable();
                    $table->timestamps();

                    // We'll need to ensure that MySQL uses the InnoDB engine to
                    // support the indexes, other engines aren't affected.
                    // $table->engine = 'InnoDB';
                    $table->unique('email');
                    $table->index('activation_code');
                    $table->index('reset_password_code');
                });
            }

            if ( Schema::hasTable('groups') == false ) {
                Schema::create('groups', function ($table) {
                    $table->increments('id');
                    $table->string('name');
                    $table->text('permissions')->nullable();
                    $table->timestamps();

                    // We'll need to ensure that MySQL uses the InnoDB engine to
                    // support the indexes, other engines aren't affected.
                    // $table->engine = 'InnoDB';
                    $table->unique('name');
                });
            }

            if ( Schema::hasTable('users_groups') == false ) {

                Schema::create('users_groups', function ($table) {
                    $table->integer('user_id')->unsigned();
                    $table->integer('group_id')->unsigned();

                    // We'll need to ensure that MySQL uses the InnoDB engine to
                    // support the indexes, other engines aren't affected.
                    // $table->engine = 'InnoDB';
                    $table->primary(array('user_id', 'group_id'));
                });
            }


            /* Userpermissions */
            if ( Schema::hasTable('userspermissions') == false ) {
                Schema::create('userspermissions', function ($table) {
                    $table->increments('id');
                    $table->text('clients')->nullable();
                    $table->text('jobs')->nullable();
                });
            }

            if ( Schema::hasTable('groupspermissions') == false ) {

                /* Groupspermissions */
                Schema::create('groupspermissions', function ($table) {
                    $table->increments('id');
                    $table->text('clients')->nullable();
                    $table->text('jobs')->nullable();
                });
            }

            if ( Schema::hasTable('throttle') == false ) {

                Schema::create('throttle', function ($table) {
                    $table->increments('id');
                    $table->integer('user_id')->unsigned();
                    $table->string('ip_address')->nullable();
                    $table->integer('attempts')->default(0);
                    $table->boolean('suspended')->default(0);
                    $table->boolean('banned')->default(0);
                    $table->timestamp('last_attempt_at')->nullable();
                    $table->timestamp('suspended_at')->nullable();
                    $table->timestamp('banned_at')->nullable();

                    // We'll need to ensure that MySQL uses the InnoDB engine to
                    // support the indexes, other engines aren't affected.
                    //$table->engine = 'InnoDB';
                });
            }



            // Create rules table
           /* Schema::create(Config::get('sentry::sentry.table.rules'), function ($table) {
                $table->on(Config::get('sentry::sentry.db_instance'));
                $table->increments('id')->unsigned();
                $table->string('rule')->unique();
                $table->string('description')->nullable();
                $table->create();
            });*/


            /* Settings Table */

            if (!Schema::hasTable('settings')) {
                Schema::create('settings', function ($table) {
                    $table->integer('id')->nullable();
                    $table->boolean('ldapon');
                    $table->string('ldapserver')->nullable();
                    $table->string('ldapdomain')->nullable();
                    $table->string('ldapuser')->nullable();
                    $table->string('ldappassword')->nullable();
                    $table->string('ldapport')->nullable();
                    $table->string('servername')->nullable();
                    $table->string('adminemail')->nullable();
                    $table->string('logo')->nullable();
                    $table->string('confdir')->nullable();


                });
            }

            if (!Schema::hasTable('filessearch')) {
                Schema::create('filessearch', function ($table) {
                    $table->increments('id');
                    $table->integer('jobid')->nullable();
                    $table->string('path')->nullable();
                    $table->string('filename')->nullable();
                });
            }


            /// Configuration Bacula Tables /////

             if (!Schema::hasTable('cfgconsole')) {
                /* cfgFileSetExclude */
                Schema::create('cfgconsole', function ($table) {
                    $table->increments('id');
                    $table->string('Name')->nullable();
                    $table->string('Password')->nullable();
                    $table->string('JobACL')->nullable();
                    $table->string('ClientACL')->nullable();
                    $table->string('StorageACL')->nullable();
                    $table->string('ScheduleACL')->nullable();
                    $table->string('PoolACL')->nullable();
                    $table->string('FileSetACL')->nullable();
                    $table->string('CatalogACL')->nullable();
                    $table->string('CommandACL')->nullable();
                    $table->string('WhereACL')->nullable();

                });
            }


             if (!Schema::hasTable('cfgmessages')) {
                /* cfgFileSetExclude */
                Schema::create('cfgmessages', function ($table) {
                    $table->increments('id');
                    $table->string('Name')->nullable();
                    $table->string('MailCommand')->nullable();
                    $table->string('OperatorCommand')->nullable();
                    $table->string('destination')->nullable();
                    $table->string('append')->nullable();
                    $table->string('operator')->nullable();
                    $table->string('console')->nullable();
                    $table->string('mail')->nullable();
                    $table->string('mailonerror')->nullable();
                    $table->string('catalog')->nullable();

                });
            }



            if (!Schema::hasTable('cfgfilesetexclude')) {
                /* cfgFileSetExclude */
                Schema::create('cfgfilesetexclude', function ($table) {
                    $table->increments('id');
                    $table->integer('idfileset')->nullable();
                    $table->string('file')->nullable();

                });
            }

            /* cfgFileSetInclude  */
            if (!Schema::hasTable('cfgfilesetinclude')) {
                Schema::create('cfgfilesetinclude', function ($table) {
                    $table->increments('id');
                    $table->integer('idfileset')->nullable();
                    $table->string('file')->nullable();

                });
            }

            /* cfgFileSetIncludeOptions   */
            if (!Schema::hasTable('cfgfilesetincludeoptions')) {
                Schema::create('cfgfilesetincludeoptions', function ($table) {
                    $table->increments('id');
                    $table->integer('idfileset')->nullable();
                    $table->string('option')->nullable();
                    $table->string('value')->nullable();

                });

            }

             /* cfgFileSetIncludeOptions   */
            if (!Schema::hasTable('cfgfilesetexcludeoptions')) {
                Schema::create('cfgfilesetexcludeoptions', function ($table) {
                    $table->increments('id');
                    $table->integer('idfileset')->nullable();
                    $table->string('option')->nullable();
                    $table->string('value')->nullable();
                });

            }

            /* cfgcatalog */
            if (!Schema::hasTable('cfgcatalog')) {
                Schema::create('cfgcatalog', function ($table) {
                    $table->increments('id');
                    $table->string('Name')->nullable();
                    $table->string('DBPassword')->nullable();
                    $table->string('DBName')->nullable();
                    $table->string('DBUser')->nullable();
                    $table->string('DBSocket')->nullable();
                    $table->string('DBAddress')->nullable();
                    $table->string('DBPort')->nullable();

                });
            }

            /* cfgclient */
            if (!Schema::hasTable('cfgclient')) {
                Schema::create('cfgclient', function ($table) {
                    $table->increments('id');
                    $table->string('Name')->nullable();
                    $table->string('Address')->nullable();
                    $table->string('FDPort')->nullable();
                    $table->string('Catalog')->nullable();
                    $table->string('Password')->nullable();
                    $table->string('FileRetention')->nullable();
                    $table->string('JobRetention')->nullable();
                    $table->string('AutoPrune')->nullable();
                    $table->string('MaximumConcurrentJobs')->nullable();
                    $table->string('Priority')->nullable();
                    $table->string('HeartbeatInterval')->nullable();

                });
            }


            /* cfgdirector */

            if (!Schema::hasTable('cfgdirector')) {
                Schema::create('cfgdirector', function ($table) {
                    $table->increments('id');
                    $table->string('Name')->nullable();
                    $table->string('Description')->nullable();
                    $table->string('Password')->nullable();
                    $table->string('Messages')->nullable();
                    $table->string('PidDirectory')->nullable();
                    $table->string('ScriptsDirectory')->nullable();
                    $table->string('QueryFile')->nullable();
                    $table->string('HeartbeatInterval')->nullable();
                    $table->string('MaximumConcurrentJobs')->nullable();
                    $table->string('FDConnectTimeout')->nullable();
                    $table->string('SDConnectTimeout')->nullable();
                    $table->string('DirPort')->nullable();
                    $table->string('DirAddress')->nullable();
                    $table->string('DirSourceAddress')->nullable();
                    $table->string('StatisticsRetention')->nullable();
                    $table->string('MaximumConsoleConnections')->nullable();
                    $table->string('VerId')->nullable();
                    $table->string('WorkingDirectory')->nullable();
                });
            }

            /* cfgfileset */
            if (!Schema::hasTable('cfgfileset')) {
                Schema::create('cfgfileset', function ($table) {
                    $table->increments('id');
                    $table->string('Name')->nullable();
                    $table->string('IgnoreFileSetChanges')->nullable();
                    $table->string('EnableVSS')->nullable();

                });
            }

             /* cfgfilesetexcludeoptions */
            if (!Schema::hasTable('cfgfilesetexcludeoptions')) {
                Schema::create('cfgfilesetexcludeoptions', function ($table) {
                    $table->increments('id');
                    $table->integer('idfileset')->nullable();
                    $table->string('option')->nullable();
                    $table->string('value')->nullable();
                });
            }

            /* cfgjob */
            if (!Schema::hasTable('cfgjob')) {
                Schema::create('cfgjob', function ($table) {
                    $table->increments('id');
                    $table->string('Name')->nullable();
                    $table->string('Enabled')->nullable();
                    $table->string('Type')->nullable();
                    $table->string('Level')->nullable();
                    $table->string('Accurate')->nullable();
                    $table->string('VerifyJob')->nullable();
                    $table->string('JobDefs')->nullable();
                    $table->string('Bootstrap')->nullable();
                    $table->string('WriteBootstrap')->nullable();
                    $table->string('Client')->nullable();
                    $table->string('FileSet')->nullable();
                    $table->string('Base')->nullable();
                    $table->string('Messages')->nullable();
                    $table->string('Pool')->nullable();
                    $table->string('FullBackupPool')->nullable();
                    $table->string('MaximumBandwidth')->nullable();
                    $table->string('IncrementalBackupPool')->nullable();
                    $table->string('Storage')->nullable();
                    $table->string('DifferentialBackupPool')->nullable();
                    $table->string('Schedule')->nullable();
                    $table->string('MaxRunTime')->nullable();
                    $table->string('DifferentialMaxWaitTime')->nullable();
                    $table->string('MaxRunSchedTime')->nullable();
                    $table->string('MaxWaitTime')->nullable();
                    $table->string('MaxStartDelay')->nullable();
                    $table->string('PruneJobs')->nullable();
                    $table->string('PreferMountedVolumes')->nullable();
                    $table->string('IncrementalMaxRunTime')->nullable();
                    $table->string('PruneVolumes')->nullable();
                    $table->string('SpoolData')->nullable();
                    $table->string('RunBeforeJob')->nullable();
                    $table->string('RunAfterJob')->nullable();
                    $table->string('RunAfterFailedJob')->nullable();
                    $table->string('ClientRunBeforeJob')->nullable();
                    $table->string('ClientRunAfterJob')->nullable();
                    $table->string('RerunFailedLevels')->nullable();
                    $table->string('MaxFullInterval')->nullable();
                    $table->string('SpoolSize')->nullable();
                    $table->string('Where')->nullable();
                    $table->string('AddPrefix')->nullable();
                    $table->string('RegexWhere')->nullable();
                    $table->string('StripPrefix')->nullable();
                    $table->string('MaximumConcurrentJobs')->nullable();
                    $table->string('RescheduleInterval')->nullable();
                    $table->string('PrefixLinks')->nullable();
                    $table->string('RescheduleOnError')->nullable();
                    $table->string('Replace')->nullable();
                    $table->string('AllowMixedPriority')->nullable();
                    $table->string('Priority')->nullable();
                    $table->string('AllowHigherDuplicates')->nullable();
                    $table->string('CancelLowerLevelDuplicates')->nullable();
                    $table->string('CancelQueuedDuplicates')->nullable();
                    $table->string('RescheduleTimes')->nullable();
                    $table->string('AllowDuplicateJobs')->nullable();
                    $table->string('CancelRunningDuplicates')->nullable();
                    $table->string('SpoolAttributes')->nullable();
                    $table->string('WritePartAfterJob')->nullable();
                    $table->string('Run')->nullable();
                    $table->string('PruneFiles')->nullable();
                });
            }

             /* cfgpool */
            if (!Schema::hasTable('cfgpool')) {

                Schema::create('cfgpool', function ($table) {
                    $table->increments('id');
                    $table->string('Name')->nullable();
                    $table->string('MaximumVolumes')->nullable();
                    $table->string('PoolType')->nullable();
                    $table->string('Storage')->nullable();
                    $table->string('UseVolumeOnce')->nullable();
                    $table->string('MaximumVolumeJobs')->nullable();
                    $table->string('MaximumVolumeFiles')->nullable();
                    $table->string('MaximumVolumeBytes')->nullable();
                    $table->string('VolumeUseDuration')->nullable();
                    $table->string('CatalogFiles')->nullable();
                    $table->string('AutoPrune')->nullable();
                    $table->string('VolumeRetention')->nullable();
                    $table->string('ActionOnPurge')->nullable();
                    $table->string('ScratchPool')->nullable();
                    $table->string('RecyclePool')->nullable();
                    $table->string('RecycleOldestVolume')->nullable();
                    $table->string('RecycleCurrentVolume')->nullable();
                    $table->string('Recycle')->nullable();
                    $table->string('PurgeOldestVolume')->nullable();
                    $table->string('FileRetention')->nullable();
                    $table->string('JobRetention')->nullable();
                    $table->string('CleaningPrefix')->nullable();
                    $table->string('LabelFormat')->nullable();

                });
            }


            /* cfgschedule */
            if (!Schema::hasTable('cfgschedule')) {

                Schema::create('cfgschedule', function ($table) {
                    $table->increments('id');
                    $table->string('Name')->nullable();
                    $table->string('Run')->nullable();
                });
            }

            /* cfgscheduleRun */
            if (!Schema::hasTable('cfgschedulerun')) {

                Schema::create('cfgschedulerun', function ($table) {
                    $table->increments('id');
                    $table->string('idschedule')->nullable();
                    $table->string('Run')->nullable();
                });
            }

            /* cfgstorage */
            if (!Schema::hasTable('cfgstorage')) {

                Schema::create('cfgstorage', function ($table) {
                    $table->increments('id');
                    $table->string('Name')->nullable();
                    $table->string('Run')->nullable();
                    $table->string('SDPort')->nullable();
                    $table->string('Password')->nullable();
                    $table->string('Device')->nullable();
                    $table->string('MediaType')->nullable();
                    $table->string('Autochanger')->nullable();
                    $table->string('MaximumConcurrentJobs')->nullable();
                    $table->string('AllowCompression')->nullable();
                    $table->string('HeartbeatInterval')->nullable();
                    $table->string('Address')->nullable();

                });
            }

             /* daystats */
            if (!Schema::hasTable('daystats')) {

                Schema::create('daystats', function ($table) {
                    $table->increments('id');
                    $table->timestamp('data')->nullable();
                    $table->string('server')->nullable();
                    $table->bigInteger('bytes')->nullable();
                    $table->bigInteger('files')->nullable();
                    $table->integer('clients')->nullable();
                    $table->bigInteger('databasesize')->nullable();
                });
            }


             /* hoursstats */
            if (!Schema::hasTable('hoursstats')) {

                Schema::create('hoursstats', function ($table) {
                    $table->increments('id');
                    $table->timestamp('data')->nullable();
                    $table->string('server')->nullable();
                    $table->timestamp('starttime')->nullable();
                    $table->timestamp('endtime')->nullable();
                    $table->bigInteger('bytes')->nullable();
                    $table->bigInteger('hoursdiff')->nullable();
                    $table->double('hourbytes')->nullable();
                    $table->string('timediff')->nullable();

                });
            }

            //Group::where('name', '=', 'Admins')->count();
            //var_dump ($count);

            /* If Not Found Create Admin Group */
            if (!Group::where('name', '=', 'Admins')->count()){
               $group = Sentry::createGroup(array(
                    'name'        => 'Admins',
                    'permissions' => array(
                        'admin' => 1,
                        'users' => 1,
                    ),
                ));
            } else {
                $group = Sentry::findGroupByName('Admins');
            }
             // Create User
             if (!User::where('email', '=', Input::get('email'))->count()) {
                $user = Sentry::createUser(array(
                     'email'    => Input::get('email'),
                     'password' => Input::get('password'),
                     'activated'  => '1',
                ));
                $user->addGroup($group);
            }
            /* Emails Tables */
            if ( Schema::hasTable('emails') == false ) {
                Schema::create('emails', function ($table) {
                    $table->increments('id');
                    $table->text('emails');
                    $table->text('clients')->nullable();
                    $table->text('jobs')->nullable();
                    $table->text('when')->nullable();
                });
            }
            echo json_encode(array('location' =>  'install/installSucess'));
        } catch (Sentry\SentryException $e) {
            $errors = new Laravel\Messages();
            Session::flash('status_error', $e->getMessage());

            return Redirect::to('install')->with_errors($validation);
        }
    }

}
