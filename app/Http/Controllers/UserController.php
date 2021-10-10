<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function index()
    {
        $all_users = User::all();
        return $all_users;
    }

    public function show($id)
    {
        $show_user = User::find($id);
        return $show_user;
    }

    public function update(Request $request, $id)
    {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user && $user->id == $id) {
            $user = User::find($id);
            $user->update($request->all());
            return response([
                'message' => 'User succesfuly updated',
                'user' => $user
            ]);
        }
        else {
            return response([
                'message' => 'You have no rights to update user'
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user && $user->id == $id) {
            User::destroy($id);
            return response([
                'message' => 'User succesfuly deleted'
            ]);
        }
        else {
            return response([
                'message' => 'You have no rights to delete users'
            ]);
        }
    }
}
