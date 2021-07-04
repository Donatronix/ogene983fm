<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        //validate the fields....
        $request->flash();
        // dd($request);
        // validate the info, create rules for the inputs
        $this->validate($request, [
            'email'    => 'required|string',   // make sure the email is an actual email
            'password' => 'required|min:6',         // password can only be alphanumeric and has to be greater than 3 characters
        ]);
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials, $request->remember)) { // login attempt
                //login successful, redirect the user to your preferred url/route...
                return redirect()->intended('/');
            }
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return back();
        }
        //login failed...
        return redirect("/login");
    }

    public function logout()
    {
        try {
            Auth::logout(); // log the user out of our application
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return back();
        }
        return redirect('/'); // redirect the user to the index page
    }
}
