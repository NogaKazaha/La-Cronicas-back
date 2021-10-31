<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => "NogaKazaha",
            'email' => "nogakazahawork@gmail.com",
            'password' => Hash::make('qweasdzxc'),
            'shareId' => 'NogaKazaha',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('calendars')->insert([
            'user_id' => 1,
            'title' => 'Default calendar',
            'status' => 'unremovable',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('events')->insert([
            'calendar_id' => 1,
            'user_id' => 1,
            'title' => 'My birthday',
            'description' => 'This is My birthday',
            'category' => 'reminder',
            'date' => '2001-08-13',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('calendars_users_ids')->insert([
            'calendar_id' => 1,
            'user_id' => 1,
            'owner' => true
        ]);
    }
}
