<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array('prefix' => 'install'), function () {
    Route::any('/',                		'app\controllers\InstallController@getInstall');
    Route::resource('testDb',      		'app\controllers\InstallController@testDb');
    Route::resource('installSucess',    'app\controllers\InstallController@installSucess');
    Route::resource('installSave',      'app\controllers\InstallController@installSave');
});

/* Not Logged admin Routes*/
Route::get('admin/logout',  array('as' => 'admin.logout',      'uses' => 'app\controllers\admin\AuthController@getLogout'));
Route::get('admin/login',   array('as' => 'admin.login',       'uses' => 'app\controllers\admin\AuthController@getLogin'));
Route::post('admin/login',  array('as' => 'admin.login.post',  'uses' => 'app\controllers\admin\AuthController@postLogin'));


/* Not Logged Normal Routes*/
Route::get('logout',  array('as' => 'logout',      'uses' => 'app\controllers\AuthController@getLogout'));
Route::get('login',   array('as' => 'login',       'uses' => 'app\controllers\AuthController@getLogin'));
Route::post('login',  array('as' => 'login.post',  'uses' => 'app\controllers\AuthController@postLogin'));

/* Routes For admin Panel */
Route::group(array('prefix' => 'admin', 'before' => 'auth.admin'), function () {
    Route::any('/',                'app\controllers\admin\DashboardController@dashboard');
    Route::resource('articles',    'app\controllers\admin\ArticlesController');

    Route::get('dashboard',  array('as' => 'admin.dashboard', 'uses' => 'app\controllers\admin\DashboardController@dashboard'));

    //Emails Controllers
    Route::get('emails',           array('as' => 'admin.emails',     'uses' => 'app\controllers\admin\EmailsController@emails'));
    Route::get('getemails',        array('as' => 'admin.getemails',  'uses' => 'app\controllers\admin\EmailsController@getemails'));
    Route::get('createemails',      array('as' => 'admin.createemails','uses' => 'app\controllers\admin\EmailsController@createemails'));
    Route::get('editemails/{id}',   array('as' => 'admin.editemails',  'uses' => 'app\controllers\admin\EmailsController@editemails'));
    Route::get('deleteemails/{id}', array('as' => 'admin.deleteemails','uses' => 'app\controllers\admin\EmailsController@deleteemails'));
    Route::post('saveemails',       array('as' => 'admin.saveemails',  'uses' => 'app\controllers\admin\EmailsController@saveemails'));

    //User Controllers
    Route::get('users',           array('as' => 'admin.users',     'uses' => 'app\controllers\admin\UsersController@users'));
    Route::get('getusers',        array('as' => 'admin.getusers',  'uses' => 'app\controllers\admin\UsersController@getusers'));
    Route::get('createuser',      array('as' => 'admin.createuser','uses' => 'app\controllers\admin\UsersController@createuser'));
    Route::get('edituser/{id}',   array('as' => 'admin.edituser',  'uses' => 'app\controllers\admin\UsersController@edituser'));
    Route::get('deleteuser/{id}', array('as' => 'admin.deleteuser','uses' => 'app\controllers\admin\UsersController@deleteuser'));
    Route::post('saveuser',       array('as' => 'admin.saveuser',  'uses' => 'app\controllers\admin\UsersController@saveuser'));

    //Groups Controllers
    Route::get('groups',           array('as' => 'admin.groups',     'uses' => 'app\controllers\admin\GroupsController@groups'));
    Route::get('getgroups',        array('as' => 'admin.getgroups',  'uses' => 'app\controllers\admin\GroupsController@getgroups'));
    Route::get('creategroup',      array('as' => 'admin.creategroup','uses' => 'app\controllers\admin\GroupsController@creategroup'));
    Route::get('editgroup/{id}',   array('as' => 'admin.editgroup',  'uses' => 'app\controllers\admin\GroupsController@editgroup'));
    Route::get('deletegroup/{id}', array('as' => 'admin.deletegroup','uses' => 'app\controllers\admin\GroupsController@deletegroup'));
    Route::post('savegroup',       array('as' => 'admin.savegroup',  'uses' => 'app\controllers\admin\GroupsController@savegroup'));

    //Settings Controllers
    Route::get('settings',         array('as' => 'admin.settings',     'uses' => 'app\controllers\admin\SettingsController@settings'));
    Route::post('savesettings',    array('as' => 'admin.savesettings', 'uses' => 'app\controllers\admin\SettingsController@savesettings'));
    Route::get('settings/ldap',    array('as' => 'admin.settings.ldap',     'uses' => 'app\controllers\admin\SettingsController@ldap'));

    Route::resource('settings/testLdap',     'app\controllers\admin\SettingsController@testLdap');
    Route::resource('settings/syncLdap',    'app\controllers\admin\SettingsController@syncLdap');

    //Configurator Controllers
    Route::get('configurator',  array('as' => 'admin.configurator',  'uses' => 'app\controllers\admin\ConfiguratorController@configurator'));
    Route::get('readbacula',    array('as' => 'admin.readbacula',    'uses' => 'app\controllers\admin\ConfiguratorController@readbacula'));
    Route::get('writebacula',    array('as' => 'admin.writebacula',    'uses' => 'app\controllers\admin\ConfiguratorController@writebacula'));
    Route::get('restartbacula',    array('as' => 'admin.restartbacula',    'uses' => 'app\controllers\admin\ConfiguratorController@restartbacula'));
    Route::get('gettreedata',   array('as' => 'admin.gettreedata',   'uses' => 'app\controllers\admin\ConfiguratorController@gettreedata'));
    Route::post('getnode',       array('as' => 'admin.getnode',       'uses' => 'app\controllers\admin\ConfiguratorController@getnode'));
    Route::get('getincludes',        array('as' => 'admin.getincludes',       'uses' => 'app\controllers\admin\ConfiguratorController@getincludes'));
    Route::get('getincludesoptions', array('as' => 'admin.getincludesoptions',       'uses' => 'app\controllers\admin\ConfiguratorController@getincludesoptions'));
    Route::get('getexcludes',        array('as' => 'admin.getexcludes',       'uses' => 'app\controllers\admin\ConfiguratorController@getexcludes'));
    Route::get('getexcludesoptions', array('as' => 'admin.getexcludesoptions',       'uses' => 'app\controllers\admin\ConfiguratorController@getexcludesoptions'));
    Route::get('getschedule',        array('as' => 'admin.getschedule',       'uses' => 'app\controllers\admin\ConfiguratorController@getschedule'));

    Route::post('addincludes', array('as' => 'admin.addincludes',       'uses' => 'app\controllers\admin\ConfiguratorController@addincludes'));
    Route::post('deleteincludes', array('as' => 'admin.deleteincludes',       'uses' => 'app\controllers\admin\ConfiguratorController@deleteincludes'));

    Route::post('addincludesoptions', array('as' => 'admin.addincludesoptions',       'uses' => 'app\controllers\admin\ConfiguratorController@addincludesoptions'));
    Route::post('deleteincludesoptions', array('as' => 'admin.deleteincludesoptions',       'uses' => 'app\controllers\admin\ConfiguratorController@deleteincludesoptions'));
    Route::post('addexcludes', array('as' => 'admin.addexcludes',       'uses' => 'app\controllers\admin\ConfiguratorController@addexcludes'));
    Route::post('deleteexcludes', array('as' => 'admin.deleteexcludes',       'uses' => 'app\controllers\admin\ConfiguratorController@deleteexcludes'));

    Route::post('addexcludesoptions', array('as' => 'admin.addexcladdSchedulerunudesoptions',       'uses' => 'app\controllers\admin\ConfiguratorController@addexcludesoptions'));

    Route::post('deleteexcludesoptions', array('as' => 'admin.deleteexcludesoptions',       'uses' => 'app\controllers\admin\ConfiguratorController@deleteexcludesoptions'));

    Route::post('addSchedulerun', array('as' => 'admin.addSchedulerun',       'uses' => 'app\controllers\admin\ConfiguratorController@addSchedulerun'));

    Route::post('deleteSchedulerun', array('as' => 'admin.deleteSchedulerun',       'uses' => 'app\controllers\admin\ConfiguratorController@deleteSchedulerun'));

    /* Console Route */
    Route::get('console', array('as' => 'admin.console',       'uses' => 'app\controllers\admin\ConsoleController@console'));



    /* Configuration Editor Saves*/
    Route::post('saveconfiguration', array('as' => 'admin.saveconfiguration', 'uses' => 'app\controllers\admin\ConfiguratorController@saveconfiguration'));

    /* Configuration New Item*/
    Route::post('newitem', array('as' => 'admin.newitem', 'uses' => 'app\controllers\admin\ConfiguratorController@newitem'));

    /* Delete Configuration Item*/
    Route::post('deleteitem', array('as' => 'admin.deleteitem', 'uses' => 'app\controllers\admin\ConfiguratorController@deleteitem'));

    /* Console commands */
    Route::post('command', array('as' => 'admin.command', 'uses' => 'app\controllers\admin\ConsoleController@command'));


});


/* Routes For Reportula App Loged Users */
Route::group(array('before' => 'auth'), function () {
    // Dashboard
    Route::any('/',                'app\controllers\DashboardController@dashboard');
    Route::get('dashboard/getjobs',    array('as' => 'dashboard.getjobs',    'uses' => 'app\controllers\DashboardController@getjobs'));
    Route::get('dashboard/getvolumes', array('as' => 'dashboard.getvolumes', 'uses' => 'app\controllers\DashboardController@getvolumes'));
    Route::get('dashboard/getgraph',   array('as' => 'dashboard.getgraph',   'uses' => 'app\controllers\DashboardController@getgraph'));
    Route::get('dashboard/test',    array('as' => 'dashboard.test',    'uses' => 'app\controllers\DashboardController@test'));

    Route::get('dashboard/{data?}',    array('as' => 'dashboard',            'uses' => 'app\controllers\DashboardController@dashboard'), function ($data = 'day') { return $data; });

    // Clients
    Route::get('clients',               array('as' => 'clients',               'uses' => 'app\controllers\ClientsController@clients'));
    Route::get('clients/getclients',    array('as' => 'clients.getclients',    'uses' => 'app\controllers\ClientsController@getclients'));
    Route::post('clients',              array('as' => 'client',                'uses' => 'app\controllers\ClientsController@clients'));

    // Jobs
    Route::get('jobs',               array('as' => 'jobs',               'uses' => 'app\controllers\JobsController@jobs'));
    Route::get('jobs/getjobs',       array('as' => 'jobs.getjobs',       'uses' => 'app\controllers\JobsController@getjobs'));

    Route::post('jobs',              array('as' => 'job',                'uses' => 'app\controllers\JobsController@jobs'));
    Route::get('jobs/{jobs?}',       array('as' => 'jobs',               'uses' => 'app\controllers\JobsController@jobs'), function ($jobs = 'null') { return $jobs; });

    // Files
    Route::get('files/getfiles',      array('as' => 'files.getfiles',      'uses' => 'app\controllers\FilesController@getfiles'));
    Route::get('files/{files?}',      array('as' => 'files',               'uses' => 'app\controllers\FilesController@files'), function ($files = 'null') { return $files; });

    // Volumes
    Route::get('volumes',               array('as' => 'volumes',               'uses' => 'app\controllers\VolumesController@volumes'));
    Route::get('volumes/getvolumes',    array('as' => 'volumes.getvolumes',    'uses' => 'app\controllers\VolumesController@getvolumes'));
    Route::post('volumes',              array('as' => 'volume',                'uses' => 'app\controllers\VolumesController@volumes'));
    Route::get('volumes/{volumes?}',    array('as' => 'volumes',               'uses' => 'app\controllers\VolumesController@volumes'), function ($volumes = 'null') { return $volumes; });

    // Pools
    Route::get('pools',               array('as' => 'pools',               'uses' => 'app\controllers\PoolsController@pools'));
    Route::get('pools/getpools',      array('as' => 'pools.getpools',      'uses' => 'app\controllers\PoolsController@getpools'));
    Route::post('pools',              array('as' => 'pool',                'uses' => 'app\controllers\PoolsController@pools'));

    // Statistics of The Server
    Route::get('stats',               array('as' => 'stats',               'uses' => 'app\controllers\StatsController@stats'));
    Route::get('stats/gethoursstas',  array('as' => 'stats.gethoursstas',  'uses' => 'app\controllers\StatsController@gethoursstas'));
    Route::get('stats/insertStats',  array('as' => 'stats.insertStats',  'uses' => 'app\controllers\StatsController@insertStats'));

    // Statistics of The Server
    Route::get('actions',               array('as' => 'actions',               'uses' => 'app\controllers\ActionsController@actions'));





});


Route::get('/', function () {
    Route::any('/index.php/login',                        'app\controllers\AuthController@getLogin');
});
