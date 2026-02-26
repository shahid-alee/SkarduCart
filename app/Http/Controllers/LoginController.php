<?php

namespace App\Http\Controllers;

use Dotenv\Validator;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function loginform()
    {
        return view('Auth.login');
    }

    public function loginUser(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->passes()) {
        } else {
            return redirect()->route('Auth.login')->withInput()->withErrors($validator);
        }
    }
}
