<?php namespace app\controllers\admin;

use BaseController, Datatables, View, Sentry, URL ;
use Input, Validator, Response, Former, Log, Asset, Debugbar;
use Date,Time, AppHelper, Mail;

// Models
use app\models\Emails;
use app\models\Client;
use app\models\Job;
use app\models\Userspermissions;

class EmailsController extends BaseController
{
    public $group_array            = array();

   public function __construct()
    {
        parent::__construct();
        Asset::add('multi-select', 'assets/css/multi-select.css');
        Asset::add('jquerymultiselect', 'assets/js/jquery.multi-select.js', 'jquery');
        Asset::add('eldarionform', 'assets/js/eldarion-ajax.min.js', 'jquery');
    }

    /**
     * Display the Report Emails page
     * @return View
     */
    public function emails()
    {
        return View::make('admin.emails');
    }

    /**
    * Add New Email
    * @return id user
    */
    public function createemails()
    {
        return View::make('admin.emailnewedit')->with('when', array ('Daily' => 'Daily', "Weekly"=>"Weekly","Monthly" => "Monthly"))
                                               ->with('whenSelected', '' )
                                               ->with('clientsSelected', '' )
                                               ->with('jobsSelected',    '' )
                                               ->with('clients', Client::clientSelectBox() )
                                               ->with('jobs', Job::jobSelectBox() )
                                               ->with('emails',    "" )
                                               ->with('id',       "");
    }

    /**
    * Edit Emails Reports
    * @return Array json Emails
    */
    public function editemails($id)
    {
        // Find the emailreport using the email id
        $email = Emails::find($id);
        $clientspermissions ="";
        $jobspermissions = "";
        $clientspermissions =unserialize ($email->clients);
        $jobspermissions = unserialize ($email->jobs);

        Former::populate($email);

        return View::make('admin.emailnewedit')->with('when', array ('Daily' => 'Daily', "Weekly"=>"Weekly","Monthly" => "Monthly"))
                                                ->with('whenSelected', $email->when )
                                               ->with('clients',         Client::clientSelectBox()  )
                                               ->with('clientsSelected', $clientspermissions )
                                               ->with('jobsSelected',    $jobspermissions )
                                               ->with('jobs',            Job::jobSelectBox())
                                               ->with('emails',          $email->emails )
                                               ->with('id',              $email->id)
                                            ;
    }

    /**
    * Delete Report Emails
    * @return Array json Users
    */
    public function deleteemails($id)
    {
        $email = Emails::find($id);
        $email->delete();
        return Response::json($id);
    }

    /********************
     * Save EmailReport
     *
     * Saves Email Report data
     * @access public
     * @return Response
     */
    public function saveemails()
    {
        /* Rules Edit Form */
        $rules = array(
            'emails'     => 'required',
        );
        /* Validation settings */
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            //failed to validate
            //let's go back to that form with errors, input
            $messages =  $validation->messages();
            $html='<div class="alert alert-error">';
            foreach ($messages->all() as $message) {  $html.=' '.$message.'<br>'; }
            $html.='</div>';
            Former::withErrors($validation);
            echo json_encode(array('html' => $html));
        } else {
           try {
                $emails = array () ;
                $emails['emails'] = Input::get('emails','');
                $emails['clients'] = serialize(Input::get('emailsClients'));
                $emails['jobs']    = serialize(Input::get('emailsJobs'));
                $emails['when']    = Input::get('when','');
                if (Input::get('id')==null) {
                    $email = Emails::create($emails);
                    $message = "Created";
                }else{
                    $email=Emails::find(Input::get('id'));
                    $sapo=$email->update($emails);
                    $message = "Updated";
                }
                echo json_encode(array('html' => '<div class="alert alert-success"> Email Report Sucessufull '.$message.' </div> '));
            } catch (\Exception $e) {
                echo json_encode(array('html' => '<div class="alert alert-error">'. $e->getMessage().' </div> '));
            }
        }
    }
    /**
     * Gets All emails for datatables
     * @return Array json Emails
     */
    public function getemails()
    {
        $emails = Emails::select(array('id','emails','clients',
                                    'jobs','when',));
        return Datatables::of($emails)
        ->add_column('actions', '
                     <center>
                        <a href="{{  URL::route(\'admin.editemails\', array($id) )}}" class="btn btn-info btn-mini"><i class="icon-edit icon-white"></i> Edit </a>'
                )
        ->make();
    }

    /**
     * Sends Emails via Artisan Crontab
     * @return Ok
     */
    public function sendemails()
    {
        $emails = Emails::select(array('id','emails','clients',
                                    'jobs','when',));
        return Datatables::of($emails)
        ->add_column('actions', '
                     <center>
                        <a href="{{  URL::route(\'admin.editemails\', array($id) )}}" class="btn btn-info btn-mini"><i class="icon-edit icon-white"></i> Edit </a>'
                )
        ->make();
    }





}
