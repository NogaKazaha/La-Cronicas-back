<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function checkLogIn(Request $request)
    {
        // $token = explode(" ", $request->header("Authorization"))[1];
        $token = $request->header("Authorization");
        $token = trim($token, '\"');
        $user = DB::table('users')->where("remember_token", $token);
        if($user) {
            return true;
        }
        else {
            return false;
        }
    }
}
