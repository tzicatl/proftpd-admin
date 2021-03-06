<?php

class HomeController extends BaseController {

    public function showIndex()
    {
        if (Auth::check())
        {
            return Redirect::intended('users');
        }

        return View::make('login');
    }

    public function showLogin() {
        return View::make('login');
    }

    public function doLogin () {
        if (Auth::check()) {
            return Redirect::to('users');
        }

        // validate the info, create rules for the inputs
        $rules = array(
            'user_id'    => 'required|alphaNum', // make sure the email is an actual email
            'password' => 'required|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::to('login')
                ->withErrors($validator) // send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {
            // create our user data for the authentication
            $userdata = array(
                'user_id'     => Input::get('user_id'),
                'password'  => Input::get('password')
            );

            Log::info('Attempt auth', array('context' => $userdata));
            if (Auth::attempt($userdata)) {
                return Redirect::to('users');
            } else {
                return Redirect::to('login');
            }
        }
    }

    public function doLogout () {
        Auth::logout();
        return Redirect::to('login');
    }


}
