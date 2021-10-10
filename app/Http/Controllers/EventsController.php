<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{
    public function index() {
        $all_events = Event::all();
        return $all_events;
    }

    public function show($id) {
        $show_event = Event::find($id);
        return $show_event;
    }

    public function showByCalendar($id) {
        $show_event = Event::where('calendar_id', $id)->get();
        return $show_event;
    }

    public function store(Request $request, $id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        else {
            $user = JWTAuth::toUser(JWTAuth::getToken());
            $title = $request->input('title');
            $description = $request->input('description');
            $category = $request->input('category');
            $date = $request->input('date');
            $creditianals = [
                'calendar_id' => $id,
                'user_id' => $user->id,
                'title' => $title,
                'description' => $description,
                'category' => $category,
                'date' => $date
            ];
            $create_event = Event::create($creditianals);
            return response([
                'message' => 'Event created',
                'Event' => $create_event
            ]);

        }
    }

    public function update(Request $request, $calendar_id, $event_id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        $user = JWTAuth::toUser(JWTAuth::getToken());
        $user_id = $user->id;
        $event_creator_id = DB::table('events')->where('id', $event_id)->value('user_id');
        $calendar_creator_id = DB::table('calendars')->where('id', $calendar_id)->value('user_id');
        if($user_id != $event_creator_id && $user_id != $calendar_creator_id) {
            return response([
                'message' => 'You can not update this calendar'
            ]);
        }
        else {
            $event = Event::find($event_id);
            $event->update($request->all());
            return response([
                'message' => 'Event update',
                'Event' => $event
            ]);
        }
    }

    public function destroy(Request $request, $calendar_id, $event_id) {
        $user = $this->checkLogIn($request);
        if(!$user) {
            return response([
                'message' => 'User is not logged in'
            ]);
        }
        $user = JWTAuth::toUser(JWTAuth::getToken());
        $user_id = $user->id;
        $event_creator_id = DB::table('events')->where('id', $event_id)->value('user_id');
        $calendar_creator_id = DB::table('calendars')->where('id', $calendar_id)->value('user_id');
        if($user_id != $event_creator_id && $user_id != $calendar_creator_id) {
            return response([
                'message' => 'You can not update this calendar'
            ]);
        }
        else {
            Event::destroy($event_id);
            return response([
                'message' => 'Event deleted'
            ]);
        }
    }
}
