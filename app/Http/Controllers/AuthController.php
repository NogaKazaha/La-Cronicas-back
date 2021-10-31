<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Calendars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $create_user = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'shareId' => $randomString
        ]);
        $creditianals = [
            'user_id' => $create_user->id,
            'title' => 'Default calendar',
            'status' => 'unremovable',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];
        $addDefCalendar = Calendars::create($creditianals);
        DB::table('calendars_users_ids')->insert([
            'calendar_id' => $addDefCalendar->id,
            'user_id' => $create_user->id,
            'owner' => true
        ]);
        return response([
            'message' => 'Succesfuly registered',
            'user' => $create_user
        ]);
    }

    public function login() {
        $login_data = request()->only(['email', 'password']);
        $token = JWTAuth::attempt($login_data, ['exp' => Carbon::now()->addDays(7)->timestamp]);
        if (!$token) {
            return response([
                'message' => 'Incorrect login data!'
            ], 400);
        }
        else {
            DB::table('users')->where('email', $login_data['email'])->update([
                'remember_token' => $token
            ]);
            $user_id = DB::table('users')->where('email', $login_data['email'])->value('id');
            return response([
                'message' => 'Succesfuly loged in',
                'token' => $token,
                'user_id' => $user_id
            ]);
        }
    }
    public function logout()
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            JWTAuth::invalidate(JWTAuth::getToken());
            DB::table('users')->where('remember_token', JWTAuth::getToken())->update([
                'remember_token' => ''
            ]);
            return response([
                'message' => 'Logged out'
            ]);
        }
    }
    public function reset_password() {
        $reset_password_data = request()->only(['email']);
        $email = $reset_password_data['email'];
        $new_token = Str::random(20);
        if(!User::where('email', $reset_password_data)->first()) {
            return response([
                'message' => 'No user with such email'
            ]);
        }
        else {
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $new_token
            ]);
            $details_api = [
                'title' => 'Password Reset Mail for API',
                'body' => 'Your link to reset password: http://127.0.0.1:8000/api/auth/reset_password/'.$new_token
            ];
            // $details_react = [
            //     'title' => 'Password Reset Mail for React App',
            //     'body' => 'Your link to reset password: http://localhost:3000/password_reset/'.$new_token
            // ];
            Mail::to($reset_password_data)->send(new PasswordResetMail($details_api));
            // Mail::to($reset_password_data)->send(new PasswordResetMail($details_react));
            return response([
                'message' => 'Your password reset link was sent',
                'reset_token' => $new_token
            ]);
        }
    }
    public function confirmation_token(Request $request, $token) {
        $data = DB::table('password_resets')->where('token', $request->token)->first();
        if (!$data) {
            return response([
                'message' => 'Wrong token'
            ]);
        }
        $user = User::where('email', $data->email)->first();
        if (!$user) {
            return response([
                'message' => 'Wrong email'
            ]);
        }
        $user->password = Hash::make($request->input('password'));
        $user->update();
        $user = JWTAuth::user();
        DB::table('password_resets')->where('email', $data->email)->delete();
        return response([
            'message' => 'Password changed'
        ]);
    }
}
