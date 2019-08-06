<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgottenPasswordRequest;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\ResetPasswordRequest;
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

    /**
     * Creates and saves a new user model
     * @param RegisterFormRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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

    /**
     * Attempts to verify and login user
     * @param LoginFormRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(LoginFormRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if(!$token = JWTAuth::attempt($credentials))
        {
            return response([
                'status'    => 'error',
                'msg'       => 'Invalid Credentials.'
            ], 400);
        }

        return response([
            'status' => 'success'
        ])
            ->header('Authorization', $token);

    }

    /**
     * Log out user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function logout()
    {
        JWTAuth::invalidate();

        return response([
            'status'    => 'success',
            'msg'       => 'Logged out Successfully.'
        ], 200);
    }

    /**
     * Returns user model form currently authenticated user
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response([
            'status'    => 'success',
            'data'      => $user
        ]);
    }

    /**
     * Refreshes the current JWT token, there is no method body as this is handled via
     * the JWT refresh middleware
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }

    /**
     * Creates and saves a password reset record, sends password reset email to the user
     * @param ForgottenPasswordRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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
        $token = str_replace('/', '', $token);

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

    /**
     * Checks to see if a users password reset token has expired
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function checkTokenValidity($token)
    {
        $validity = null;

        $tokenDate = DB::table('password_resets')
            ->select('created_at')
            ->where('token', $token)
            ->first();

        if($tokenDate)
        {
            $expiryDate = Carbon::now()->subMinute(15);
            if($tokenDate->created_at < $expiryDate)
            {
                return response([
                    'status'    => 'error',
                    'msg'       => 'reset link expired, please request another'
                ], 400);
            }
            else
            {
                return response([
                    'status'    => 'success',
                    'msg'       => 'token valid'
                ]);
            }
        }
        else
        {
            return response([
                'status'    => 'error',
                'msg'       => 'reset link invalid, please request another'
            ], 400);
        }
    }

    /**
     * Updates the users password
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $userEmail = DB::table('password_resets')
            ->select('email')
            ->where('token', $request->token)
            ->first();

        if($userEmail)
        {
            $user = User::where('email', $userEmail->email)
                ->first();
            $user->password = bcrypt($request->password);
            $user->save();

            return response([
                'status'    => 'success',
                'msg'       => 'password reset'
            ]);
        }
        else
        {
            return response([
                'status'    => 'error',
                'msg'       => 'reset link invalid, please request another'
            ], 400);
        }
    }
}
