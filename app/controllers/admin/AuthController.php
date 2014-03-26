<?php

namespace app\controllers\admin;
use Auth, BaseController, Form, Input, Redirect, Sentry, View, Log, Asset;

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
       return View::make('admin.auth.login');
    }

    /**
     * Login action
     * @return Redirect
     */
    public function postLogin()
    {

        $credentials = array(
            'email'    => Input::get('username'),
            'password' => Input::get('password')
        );

        try {
            $user = Sentry::authenticate($credentials, false);
            if ($user) {
                echo json_encode(array('location' => 'dashboard'));
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

    /**
     * Logout action
     * @return Redirect
     */
    public function getLogout()
    {
        Sentry::logout();

        return Redirect::route('admin.login');
    }

}
