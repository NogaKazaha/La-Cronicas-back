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
            'created_at' => Carbon::now()->addHours(2)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->addHours(2)->format('Y-m-d H:i:s')
        ]);

        DB::table('calendars')->insert([
            'user_id' => 1,
            'title' => 'Default calendar',
            'status' => 'unremovable',
            'created_at' => Carbon::now()->addHours(2)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->addHours(2)->format('Y-m-d H:i:s')
        ]);

        DB::table('events')->insert([
            'calendar_id' => 1,
            'user_id' => 1,
            'title' => 'My birthday',
            'description' => 'This is My birthday',
            'category' => 'reminder',
            'date' => '2001-08-13',
            'created_at' => Carbon::now()->addHours(2)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->addHours(2)->format('Y-m-d H:i:s')
        ]);
        DB::table('calendars_users_ids')->insert([
            'calendar_id' => 1,
            'user_id' => 1,
            'owner' => true
        ]);
        $key = '19268055-e430-4fe9-9594-3aa7d963929b';
        $holiday_api = new \HolidayAPI\Client(['key' => $key]);
        $holidays = $holiday_api->holidays([
            'country' => 'UA',
            'year' => date("Y") - 1,
        ]);
        $holidays = $holidays['holidays'];
        foreach ($holidays as $holiday => $value) {
            DB::table('events')->insert([
                'calendar_id' => 1,
                'user_id' => 1,
                'title' => $value['name'],
                'description' => $value['name'],
                'category' => 'reminder',
                'date' => Carbon::createFromFormat('Y-m-d', $value['date'])->setTime(12, 0)->addYear(),
                'created_at' => Carbon::now()->addHours(2)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->addHours(2)->format('Y-m-d H:i:s')
            ]);
        }
    }
}
