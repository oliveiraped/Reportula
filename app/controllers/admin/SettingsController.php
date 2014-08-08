<?php namespace app\controllers\admin;

use BaseController, View, Sentry, URL, Input, Validator, Response, Former, Log, Asset, Vd\Vd;
use adLDAP, DB;

// Models
use app\models\Settings;
use app\models\User;

class SettingsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        Asset::add('jquery2', 'assets/js/jquery-2.0.3.min.js');
        Asset::add('multi-select', 'assets/css/multi-select.css');
        Asset::add('jquerymultiselect', 'assets/js/jquery.multi-select.js', 'jquery');
        Asset::add('eldarionform', 'assets/js/eldarion-ajax.min.js', 'jquery');
        Asset::add('bootbox.js', 'assets/js/bootbox.min.js', 'jquery');
        Asset::add('settings.js', 'assets/js/settings.js', 'jquery');

    }

    /**
     * Display Settings page
     * @return View
     */
    public function settings()
    {
        Former::populate(Settings::find(1));
        return View::make('admin.settings', array ('settings'=>Settings::find(1) ));
    }

    /********************
     * Saves Settings
     *
     * Saves user data
     * @access public
     * @return Response
     */
    public function savesettings()
    {
        /* Get Post */

        $rules = array();
        if (Input::has('ldapon')) {
            $rules = array(
                'ldapserver'    => 'required',
                'ldapdomain'    => 'required',
                'ldapuser'      => 'required',
                'ldappassword'  => 'required',
                'ldapbasedn'    => 'required',
            );
        }
        // Check Validation
        $validation = Validator::make( Input::all() , $rules);
        if ($validation->fails()) {
            //failed to validate
            //let's go back to that form with errors, input
            $messages =  $validation->messages();
            $html='<div class="response"><div class="alert alert-error">';
            foreach ($messages->all() as $message) {  $html.=' '.$message.'<br>'; }
            $html.='</div></div>';
            Former::withErrors($validation);
            echo json_encode(array('html' => $html));

        } else {
            $settings = Settings::find(1);
            if ($settings==null) $settings = new Settings;
            $settings->id           = '1';
            $settings->ldapon       = "0";
            $settings->ldapserver   = "";
            $settings->ldapdomain   = "";
            $settings->ldapuser     = "";
            $settings->ldappassword = "";
            $settings->servername   = Input::get('servername','');
            $settings->adminemail   = Input::get('adminemail','');
            $settings->confdir      = Input::get('confdir','');
            if (Input::has('ldapon')) {
                $settings->ldapon       = Input::get('ldapon');
                $settings->ldapserver   = Input::get('ldapserver','');
                $settings->ldapdomain   = Input::get('ldapdomain','');
                $settings->ldapuser     = Input::get('ldapuser','');
                $settings->ldappassword = Input::get('ldappassword','');
                $settings->ldapbasedn   = Input::get('ldapbasedn','');
                $settings->ldapport     = Input::get('ldapport','');
            }
            $settings->save();

            /* Check if Conf Dir is writables */
            echo json_encode(array('html' => '<div class="response"><div class="alert alert-success"> Settings Sucessufull Updated </div><div class="response"></div>'));
        }


    }

    /**
     * TestLdap Action
     * @return Sucess/Failed Html Code
     */
    public function testLdap()
    {
        $ldappost = Input::all();
        /* Rules Edit Form */
        $rules = array(
            'ldapdomain'    => 'required',
            'ldappassword'  => 'required',
            'ldapuser'      => 'required',
            'ldapserver'    => 'required',
            'ldappassword'  => 'required',
            'ldapbasedn'    => 'required',
            'ldapport'      => 'required',

        );

        /* Validation settings */
        $validation = Validator::make($ldappost, $rules);

        if (Input::has('ldapon')) {
          if ($validation->fails()) {
              //failed to validate
              //let's go back to that form with errors, input
              $messages =  $validation->messages();
              $html='<div class="alert alert-error">';
              foreach ($messages->all() as $message) {  $html.=' '.$message.'<br>'; }
              $html.='</div>';
              echo json_encode(array('html' => $html));
          } else {
              try {
                  $adldap = new adLDAP(array('base_dn'    => Input::get('ldapbasedn',''),
                                    'account_suffix'      => Input::get('ldapdomain',''),
                                    'admin_username'      => Input::get('ldapuser',''),
                                    'admin_password'      => Input::get('ldappassword',''),
                                    'domain_controllers'  => array(Input::get('ldapserver','')),
                                    'ad_port'             => Input::get('ldapport',''),
                                  ));

              } catch (\adLDAPException $e ) {
                  echo json_encode(array('html' => '<div class="alert alert-error"> <i class="icon-fam-cancel"></i> '.$e.' </div>') );

              }
          }
        } else {
          $html='<div class="alert alert-error"> Please select Yes checkbox !! </div>';
           echo json_encode(array('html' => $html));
        }
        echo json_encode(array('html' => '<div class="alert alert-success"> Ldap Sucessufull Connected</div> '));
    }

    /**
     * Syncs Ldap With Reportula DATABASE
     * @return View
     */
    public function syncldap()
    {

      $vd = new VD;
      $ldappost = Input::all();
      /* Rules Edit Form */
      $rules = array(
          'ldapdomain'    => 'required',
          'ldappassword'  => 'required',
          'ldapuser'      => 'required',
          'ldapserver'    => 'required',
          'ldappassword'  => 'required',
          'ldapbasedn'    => 'required',
          'ldapport'      => 'required',

      );

      /* Validation settings */
      $validation = Validator::make($ldappost, $rules);

      if (Input::has('ldapon')) {
        if ($validation->fails()) {
            //failed to validate
            //let's go back to that form with errors, input
            $messages =  $validation->messages();
            $html='<div class="alert alert-error">';
            foreach ($messages->all() as $message) {  $html.=' '.$message.'<br>'; }
            $html.='</div>';
            echo json_encode(array('html' => $html));
        } else {
          try {
                $adldap = new adLDAP(array('base_dn'    => Input::get('ldapbasedn',''),
                                  'account_suffix'      => Input::get('ldapdomain',''),
                                  'admin_username'      => Input::get('ldapuser',''),
                                  'admin_password'      => Input::get('ldappassword',''),
                                  'domain_controllers'  => array(Input::get('ldapserver','')),
                                  'ad_port'             => Input::get('ldapport',''),
                                ));

                $groups=$adldap->group()->all();
                $users=$adldap->user()->all();

                // Insert User on Database
                foreach ($users as $user) {
                  $u = $adldap->user()->infoCollection($user);
                  if (!empty($u->mail)) {
                    $userInsert['email']=$u->mail;
                    $userInsert['first_name']="";
                    $userInsert['last_name']="";
                    $userInsert['password']="reportula";
                    $userInsert['activated']="1";


                    if (!empty($u->displayname)) {
                      $displayname = explode(" ", $u->displayname);
                      $userInsert['first_name']=$displayname[0];
                      if (array_key_exists('1', $displayname)) $userInsert['last_name']=$displayname[1];
                    }
                    $bd = DB::table('users')->where('email',$u->mail)->first();
                    if (!isset($bd->email)) {
                      try {
                        Sentry::createUser($userInsert);
                      } catch (\Cartalyst\Sentry\Users\UserExistsException $e) {}
                    };
                  }
                }
                ///////////////////////////////////////////////////
                // Insert dos Groups and Members
                foreach ($groups as $group) {
                  try {
                    $id = Sentry::findGroupByName($group);
                  } catch (\Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
                      $id = Sentry::createGroup(array(
                        'name'        => $group,
                     ));
                  }
                  // Get Members of Group
                  $groupMembers = $adldap->group()->members($id->name);
                  if ($groupMembers <> null) {
                    foreach ($groupMembers as $members) {
                      $userinfo = $adldap->user()->infoCollection($members, array("mail"));
                      if ($userinfo->mail <> null) {
                        //  Log::info($userinfo->mail);
                        // Find User by Email
                        $user = Sentry::findUserByLogin($userinfo->mail);
                        // Find the group using the group id
                        $adminGroup = Sentry::findGroupById($id->id);
                        // Assign the group to the user
                        $user->addGroup($adminGroup);
                      }
                    }
                  }
                }
                } catch (\adLDAPException $e ) {
                    echo json_encode(array('html' => '<div class="alert alert-error"> <i class="icon-fam-cancel"></i> '.$e.' </div>') );

                }
            }
      } else {
        $html='<div class="alert alert-error"> Please select Yes checkbox !! </div>';
         echo json_encode(array('html' => $html));
      }
      echo json_encode(array('html' => '<div class="alert alert-success"> Ldap Sucessufull Syncronized</div> '));
    }





}
