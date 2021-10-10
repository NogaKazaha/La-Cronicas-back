<?php

namespace App\Http\Controllers;

use App\Models\Calendars;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class CalendarsController extends Controller
{
    public function index() {
        $all_calendars = Calendars::all();
        return $all_calendars;
    }

    public function show($id) {
        $show_calendar = Calendars::find($id);
        return $show_calendar;
    }

    public function store(Request $request) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            $title = $request->input('title');
            $creditianals = [
                'title' => $title,
                'user_id' => $user->id
            ];
            $create_calendar = Calendars::create($creditianals);
            return response([
                'message' => 'Calendar created',
                'Calendar' => $create_calendar
            ]);
        }
    }

    public function update(Request $request, $id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        $user = JWTAuth::toUser(JWTAuth::getToken());
        $user_id = $user->id;
        $creator_id = DB::table('calendars')->where('id', $id)->value('user_id');
        if($user_id != $creator_id) {
            return response([
                'message' => 'You can not update this calendar'
            ]);
        }
        else {
            $calendar = Calendars::find($id);
            $calendar->update($request->all());
            return response([
                'message' => 'Calendar update',
                'calendar' => $calendar
            ]);
        }
    }
    
    public function destroy(Request $request, $id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        $user = JWTAuth::toUser(JWTAuth::getToken());
        $user_id = $user->id;
        $creator_id = DB::table('calendars')->where('id', $id)->value('user_id');
        $status = $creator_id = DB::table('calendars')->where('id', $id)->value('status');
        if($user_id != $creator_id && $status == 'unremovable') {
            return response([
                'message' => 'You can not delete this calendar'
            ]);
        }
        else {
            Calendars::destroy($id);
            return response([
                'message' => 'Calendars deleted'
            ]);
        }
    }
}
