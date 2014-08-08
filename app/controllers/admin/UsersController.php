<?php namespace app\controllers\admin;

use BaseController, Datatables, View, Sentry, URL, Input, Validator, Response, Former, Log, Asset, Vd\Vd;

// Models
use app\models\User;
use app\models\Client;
use app\models\Job;
use app\models\Userspermissions;

class UsersController extends BaseController
{
    public $group_array            = array();

    public function __construct()
    {
        parent::__construct();
        Asset::add('multi-select', 'assets/css/multi-select.css');
        Asset::add('jquerymultiselect', 'assets/js/jquery.multi-select.js', 'jquery');
        Asset::add('eldarionform', 'assets/js/eldarion-ajax.min.js', 'jquery');

        // Gets Groups
        $groups = Sentry::getGroupProvider()->findAll();

        // Convert to Array to fill Group Select Box
        foreach ($groups as $key_name => $key_value) {
            $this->group_array[$key_value['id']]=$key_value['name'];
        }

    }

    /**
     * Display the users page
     * @return View
     */
    public function users()
    {
        return View::make('admin.users');
    }

    /**
    * Add New User
    * @return id user
    */
    public function createuser()
    {
        return View::make('admin.usersnewedit')->with('groups',$this->group_array)
                                               ->with('groupSelected', '' )
                                               ->with('clientsSelected', '' )
                                               ->with('jobsSelected',    '' )
                                               ->with('clients', Client::clientSelectBox()  )
                                               ->with('jobs',    Job::jobSelectBox() )
                                               ->with('email',    "" )
                                               ->with('id',       "");
    }

    /**
    * Edit User
    * @return Array json Users
    */
    public function edituser($id)
    {
        // Find the user using the user id
        $user = Sentry::getUserProvider()->findById($id);

        // Get the user groups
        $groupsOfSelected = $user->getGroups();

        foreach ($groupsOfSelected as $group) {
            $this->groupSelected[$group->id]=$group->id;
        }
        $clientspermissions ="";
        $jobspermissions = "";
        $permissions = Userspermissions::find($id);
        if ($permissions <> null) {
            $clientspermissions =unserialize ($permissions->clients);
            $jobspermissions = unserialize ($permissions->jobs);
        }

        Former::populate($user);

        //LOG::info(User::find($id));

        return View::make('admin.usersnewedit')->with('groups',          $this->group_array)
                                               ->with('groupSelected',   $this->groupSelected )
                                               ->with('clients',         Client::clientSelectBox()  )
                                               ->with('clientsSelected', $clientspermissions )
                                               ->with('jobsSelected',    $jobspermissions )
                                               ->with('jobs',            Job::jobSelectBox())
                                               ->with('email',           $user->email )
                                               ->with('id',              $user->id)
                                            ;
    }

    /**
    * Delete User
    * @return Array json Users
    */
    public function deleteuser($id)
    {
        $user = Sentry::getUserProvider()->findById(intval($id));
        // Delete the user
        $user->delete();

        return Response::json($id);
    }

    /********************
     * Save User
     *
     * Saves user data
     * @access public
     * @return Response
     */
    public function saveuser()
    {
        /* Get Post */
        $userpost = Input::all();

        /* Rules Edit Form */
        $rules = array(
            'email'     => 'required|email',
            'password'  => 'required|max:50',
        );

        /* Validation settings */
        $validation = Validator::make($userpost, $rules);

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

                    $id=Input::get('id');

                    // Find the user using the user id
                    $user = Sentry::getUserProvider()->findById(Input::get('id'));
                    $user->email       = Input::get('email');
                    $user->password    = Input::get('password');
                    $user->first_name  = Input::get('first_name', '');
                    $user->last_name   = Input::get('last_name', '');

                    // Get the user groups
                    $groupsOriginal = $user->getGroups();
                    // Remove All Ther Original From The User
                    foreach ($groupsOriginal as $group) {
                        $adminGroupRemove = Sentry::getGroupProvider()->findById($group->id);
                        $user->removeGroup($adminGroupRemove);

                    }

                    // Add the new Groups to the user
                    foreach (Input::get('usergroups') as $group) {
                        // Find the group using the group id
                        $adminGroupAdd = Sentry::getGroupProvider()->findById($group);
                        // Assign the group to the user
                        $user->addGroup($adminGroupAdd);
                    }

                    // Update the user
                    if ($user->save()) {
                         echo json_encode(array('html' => '<div class="alert alert-success"> User Sucessufull Updated </div> '));
                    } else {
                        echo json_encode(array('html' => '<div class="alert alert-error"> Error </div> '));
                    }

                } else {

                    /* Insert User on Database */
                    $user = Sentry::getUserProvider()->create(array(
                        'email'         => Input::get('email'),
                        'password'      => Input::get('password'),
                        'first_name'    => Input::get('first_name', ''),
                        'last_name'     => Input::get('last_name', ''),
                        'activated'     => '1'

                    ));
                    $id=$user->getId();

                    if (Input::has('usergroups')) {
                        foreach (Input::get('usergroups') as $group) {
                            // Find the group using the group id
                            $adminGroup = Sentry::getGroupProvider()->findById($group);
                            // Assign the group to the user
                            $user->addGroup($adminGroup);
                        }

                    } else {
                        // Find the group using the group id
                        $adminGroup = Sentry::getGroupProvider()->findById(1);
                        // Assign the group to the user
                        $user->addGroup($adminGroup);
                    }
                    echo json_encode(array('html' => '<div class="alert alert-success"> User Sucessufull Created </div> '));
                }

                /* Permissions */
                $permissions = Userspermissions::find($id);
                if ($permissions <> null) $permissions->delete();
                $permissions = new Userspermissions;
                $permissions->id = $id;
                //$vd= new VD;
                //$vd->dump(Input::get('userClients'));
               // var_dump(serialize(Input::get('userClients')));
                $permissions->clients = serialize(Input::get('userClients'));
                $permissions->jobs    = serialize(Input::get('userJobs'));
                $permissions->save();

            } catch (\Exception $e) {
                // Log::info( $e->getMessage() );
                echo json_encode(array('html' => '<div class="alert alert-error">'. $e->getMessage().' </div> '));

            }
        }

    }

    /**
     * Gets All users for datatables
     * @return Array json Users
     */
    public function getusers()
    {
        $users = User::select(array('users.id','users.email','users.permissions',
                                    'users.last_login','users.created_at',));

        return Datatables::of($users)

        ->add_column('actions', '
                     <center>
                        <a href="{{  URL::route(\'admin.edituser\', array($id) )}}" class="btn btn-info btn-mini"><i class="icon-edit icon-white"></i> Edit </a>'
                )

        ->make();
    }

}
