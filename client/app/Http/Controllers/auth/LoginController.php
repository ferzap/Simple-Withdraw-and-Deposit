<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login', [
            'title' => 'Login'
        ]);
    }

    public function register()
    {
        return view('register', [
            'title' => 'Register'
        ]);
    }

    public function registerUser(Request $request)
    {
        $validate = $request->validate([
            "email" => ["required", "email"],
            "name" => ["required"],
            "password" => ["required", "min:6"],
            "confPassword" => ["required", "min:6", "same:password"]
        ]);

        $response = $this->fetchRegisterApi($validate);
        if($response->status) {
            return redirect('/login')->with('success', 'Register success. Please login with your account');
        } else {
            return back()->with('error', $response->message);
        }
    }

    public function verify(Request $request)
    {
        // print_r($response);die;
        $validate = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required", "min:6"]
        ]);

        $response = $this->fetchLoginApi($validate);
        if($response->status) {
            $user = $response->data->user;
            $token = $response->data->token;
            $arrSess = [
                "user_id" => $user->id,
                "user_name" => $user->name,
                "user_email" => $user->email,
                "token" => $token,
            ];

            session($arrSess);
            $request->session()->regenerate();
            return redirect('/dashboard')->with('success', 'Login Berhasil');
        } else {
            return back()->with('error', $response->message);
        }
    }
    
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function fetchLoginApi($data)
    {
        try {
            $response = Http::withBody(
                json_encode($data))->post('http://127.0.0.1:3000/login');
            $results = json_decode($response->body());
            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }

    private function fetchRegisterApi($data)
    {
        try {
            $response = Http::withBody(
                json_encode($data))->post('http://127.0.0.1:3000/register');
            $results = json_decode($response->body());
            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }


}
