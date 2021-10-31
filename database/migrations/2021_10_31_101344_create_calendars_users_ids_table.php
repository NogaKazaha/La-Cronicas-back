<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarsUsersIdsTable extends Migration
{
    public function up()
    {
        Schema::create('calendars_users_ids', function (Blueprint $table) {
            $table->foreignId('calendar_id');
            $table->foreignId('user_id');
            $table->boolean('owner')->default(false);
        });
    }
    public function down()
    {
        Schema::dropIfExists('calendars_users_ids');
    }
}
