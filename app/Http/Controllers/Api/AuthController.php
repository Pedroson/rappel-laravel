<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgottenPasswordRequest;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Mail\PasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function register(RegisterFormRequest $request)
    {
        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();

        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function login(LoginFormRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if(!$token = JWTAuth::attempt($credentials))
        {
            return response([
                'status'    => 'error',
                'error'     => 'invalid.credentials',
                'msg'       => 'Invalid Credentials.'
            ], 400);
        }

        return response([
            'status' => 'success'
        ])
            ->header('Authorization', $token);

    }

    public function logout()
    {
        JWTAuth::invalidate();

        return response([
            'status'    => 'success',
            'msg'       => 'Logged out Successfully.'
        ], 200);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response([
            'status'    => 'success',
            'data'      => $user
        ]);
    }

    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }

    public function forgottenPassword(ForgottenPasswordRequest $request)
    {
        $user = User::where('email', $request->email)
            ->first();

        if(!$user)
        {
            return response([
                'status'    => 'error',
                'msg'       => 'email not recognised'
            ], 400);
        }

        $token = Hash::make('reset' . time() . $request->email . rand(0, 20));

        DB::table('password_resets')->insert([
            'email'         => $request->email,
            'token'         => $token,
            'created_at'    => Carbon::now()
        ]);

        Mail::to($user)->send(new PasswordReset($user, $token));

        return response([
            'status'    => 'success',
            'msg'       => 'a password reset email has been sent'
        ]);

    }
}
