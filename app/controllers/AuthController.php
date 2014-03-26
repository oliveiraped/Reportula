<?php

namespace app\controllers;
use Auth, BaseController, Form,File;
use Input, Redirect, Sentry, View, Log, Asset;
use adLDAP, DB;

use app\models\Settings;

class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        Asset::add('eldarionform', 'assets/js/eldarion-ajax.min.js', 'jquery');
    }
    /**
     * Display the login page
     * @return View
     */
    public function getLogin()
    {
       return View::make('login');
    }

    /**
     * Login action
     * @return Redirect
     */
    public function postLogin()
    {

        // Check if it Has Ldap Activated
        $settings = Settings::find(1);
        if ($settings<>null && $settings->ldapon=='1') {
           try {
                $adldap = new adLDAP(array('base_dn'    => $settings->ldapbasedn,
                                  'account_suffix'      => $settings->ldapdomain,
                                  'admin_username'      => $settings->ldapuser,
                                  'admin_password'      => $settings->ldappassword,
                                  'domain_controllers'  => array($settings->ldapserver),
                                  'ad_port'             => $settings->ldapport,
                                ));
                // Try Ldap Login
                $valid_login = $adldap->user()->authenticate(Input::get('username'),Input::get('password'));
                if ($valid_login==true) {
                    $user = $adldap->user()->infoCollection(Input::get('username'));
                    $user = Sentry::findUserByLogin($user->mail);

                    $user = Sentry::login($user,false);
                     echo json_encode(array('location' => 'dashboard/day'));

                } else {
                     echo json_encode(array('html' => '<div class="alert alert-error"> User was not found  </div> '));
                }
            } catch (Exception $e) {
                echo json_encode(array('html' => '<div class="alert alert-error">'. $e->getMessage().' </div> '));
            }

        } else {
            try {
                $user = Sentry::authenticate(array('email' => Input::get('username'),'password' => Input::get('password')), false);
                if ($user) {
                    echo json_encode(array('location' => 'dashboard/day'));
                }
            } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
                echo json_encode(array('html' => '<div class="alert alert-error"> Login field is required </div> '));
            } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
                echo json_encode(array('html' => '<div class="alert alert-error"> Password field is required  </div> '));
            } catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {
                echo json_encode(array('html' => '<div class="alert alert-error"> Wrong password, try again  </div> '));
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                echo json_encode(array('html' => '<div class="alert alert-error"> User was not found  </div> '));
            } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
                echo json_encode(array('html' => '<div class="alert alert-error"> User is not activated  </div> '));
            } catch (\Exception $e) {
               // Log::info( $e->getMessage() );
                echo json_encode(array('html' => '<div class="alert alert-error">'. $e->getMessage().' </div> '));
            }
        }
    }

    /**
     * Logout action
     * @return Redirect
     */
    public function getLogout()
    {
        Sentry::logout();

        return Redirect::route('login');
    }

}
