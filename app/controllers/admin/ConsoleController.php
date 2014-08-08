<?php namespace app\controllers\admin;

use BaseController;
use Datatables;
use View;
use Sentry;
use URL;
use Input;
use Validator;
use Response;
use Former;
use Log;
use Asset;
use Vd\Vd;
use File;
use Request;
use Debugbar;
use Cache;
use DB;
use Filesystem;
use Schema;

class ConsoleController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        Asset::add('jquery.terminal.css', 'assets/css/jquery.terminal.css');

        Asset::add('jquery', 'assets/js/jquery-2.0.3.min.js');
        Asset::add('jquery.mousewheel-min.js', 'assets/js/jquery.mousewheel-min.js');
        Asset::add('jquery.terminal-min.js', 'assets/js/jquery.terminal-min.js');
        Asset::add('console.js', 'assets/js/console.js');
    }

    /**
     * Display Console page
     * @return View
     */
    public function console()
    {
        return View::make('admin.console');
    }

    /**
     * Display Console page
     * @return View
     */
    public function command()
    {
        $output = shell_exec('echo '.Input::get('method','').' '.implode(" ",Input::get('params','')).' | bconsole');
        return Response::json(array("jsonrpc" => "2.0",
               'result' => $output,
               'id' => "1",
               'error'=> ""));
    }
}
