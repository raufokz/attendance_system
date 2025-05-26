<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckoutTimeAndLogsToAttendancesTable extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->time('checkout_time')->nullable()->after('attendance_time');
            $table->time('logs')->nullable()->after('checkout_time')->comment('Total worked time HH:MM:SS');
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('checkout_time');
            $table->dropColumn('logs');
        });
    }
}
