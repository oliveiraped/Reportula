<?php namespace app\controllers\admin;

use BaseController, Datatables, View, Sentry, URL, Input, Validator, Response, Former, Log, Asset;

// Models
use app\models\Group;
use app\models\Groupspermissions;
use app\models\Client;
use app\models\Job;

class GroupsController extends BaseController
{
    public $user_array            = array();

    public function __construct()
    {
        parent::__construct();

        Asset::add('multi-select', 'assets/css/multi-select.css');
        Asset::add('eldarionform', 'assets/js/eldarion-ajax.min.js', 'jquery');
        Asset::add('jquerymultiselect', 'assets/js/jquery.multi-select.js', 'jquery');

        // Gets Groups
        $users = Sentry::getUserProvider()->findAll();
        // Convert to Array to fill Group Select Box
        foreach ($users as $key_name => $key_value) {
            $this->user_array[$key_value['id']]=$key_value['email'];
        }
    }
    /**
     * Display the groups page
     * @return View
     */
    public function groups()
    {
        return View::make('admin.groups');
    }

    /**
    * Add New Group
    * @return View
    */
    public function creategroup()
    {
        $userSelected = "";

        return View::make('admin.groupsnewedit')->with('users',$this->user_array)
                                                ->with('userSelected', $userSelected )
                                                ->with('clientsSelected', '' )
                                                ->with('jobsSelected',    '' )
                                                ->with('clients', Client::clientSelectBox()  )
                                                ->with('jobs',    Job::jobSelectBox() )
                                                ->with('groupname',     "")
                                                ->with('id',       "");
    }

    /**
    * Edit Group
    * @return Array json groups
    */
    public function editgroup($id)
    {
        // Find the user using the user id
        $group = Sentry::getGroupProvider()->findById($id);
        $users = Sentry::getUserProvider()->findAllInGroup($group);

        // Get the user groups
        $userSelected="";


        foreach ($users as $user) {
            $userSelected[$user->id]=$user->id;
        }

        /* Get Groups Permissions */
        $clientspermissions ="";
        $jobspermissions = "";
        $permissions = Groupspermissions::find($id);
        if ($permissions <> null) {
            $clientspermissions =unserialize ($permissions->clients);
            $jobspermissions = unserialize ($permissions->jobs);
        }

        // LOG::info( $group->name);

        Former::populate( $group->id );

        return View::make('admin.groupsnewedit')->with('users',$this->user_array)
                                               ->with('userSelected', $userSelected )
                                               ->with('clients',         Client::clientSelectBox()  )
                                               ->with('clientsSelected', $clientspermissions )
                                               ->with('jobsSelected',    $jobspermissions )
                                               ->with('jobs',            Job::jobSelectBox() )
                                               ->with('groupname',      $group->name)
                                               ->with('id',             $group->id);
    }

    /**
    * Delete User
    * @return Array json groups
    */
    public function deletegroup($id)
    {
        $group = Sentry::getGroupProvider()->findById(intval($id));
        // Delete the user
        $group->delete();
        return Response::json($id);
    }

    /********************
     * Save Group
     *
     * Saves Group data
     * @access public
     * @return Response
     */
    public function savegroup()
    {
        /* Get Post */
        $grouppost = Input::all();

        /* Rules Edit Form */
        $rules = array(
            'name'     => 'required',
        );

        /* Validation settings */
        $validation = Validator::make($grouppost, $rules);

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
                /* If Has Id is Update Else is Insert */
                if (Input::has('id')) {
                    // Find the user using the user id
                    $group = Sentry::getGroupProvider()->findById(Input::get('id'));
                    $group->name       = Input::get('name');


                    // Remove All User from the group to Add the Selected One
                    $usersInGroup = Sentry::getUserProvider()->findAllInGroup($group);
                    foreach ($usersInGroup as $users) {
                        $user = Sentry::getUserProvider()->findById($users->id);
                        $user->removeGroup($group);
                    }

                    /* Adiciona users ao grupo */
                    if (Input::has('usergroups')) {
                        /*  Add User to Group*/
                        foreach (Input::get('usergroups') as $users) {
                           $user = Sentry::getUserProvider()->findById($users);
                           $user->addGroup($group);
                        }
                    }

                    // Update the group
                    if ($group->save()) {
                         echo json_encode(array('html' => '<div class="alert alert-success"> Group Sucessufull Updated </div> '));
                    } else {
                        echo json_encode(array('html' => '<div class="alert alert-error"> Error </div> '));
                    }

                } else {

                    /* Insert User on Database */
                    $group = Sentry::getGroupProvider()->create(array(
                        'name'         => Input::get('name'),
                    ));

                     /* Adiciona users ao grupo */
                    if (Input::has('usergroups')) {
                        foreach (Input::get('usergroups') as $users) {
                           $user = Sentry::getUserProvider()->findById($user);
                           $user->addGroup($group);

                        }
                    }
                    echo json_encode(array('html' => '<div class="alert alert-success"> Group Sucessufull Created </div> '));
                }
            } catch (\Exception $e) {
                echo json_encode(array('html' => '<div class="alert alert-error">'. $e->getMessage().' </div> '));

            }
        }
    }


    /**
     * Gets All groups for datatables
     * @return Array json groups
     */
    public function getgroups()
    {
        $groups = Group::select(array('groups.id','groups.name','groups.permissions',
                                    'groups.created_at'));

        return Datatables::of($groups)
        ->add_column('actions', '
                     <center>
                        <a href="{{  URL::route(\'admin.editgroup\', array($id) )}}" class="btn btn-info btn-mini"><i class="icon-edit icon-white"></i> Edit </a>
                     '
                )
        ->make();
    }

}
