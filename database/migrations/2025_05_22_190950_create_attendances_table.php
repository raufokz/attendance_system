<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('uid'); // Foreign key to users table
            $table->unsignedInteger('emp_id');
            $table->boolean('state')->default(0);
            $table->time('attendance_time');
            $table->date('attendance_date');
            $table->boolean('status')->default(1);
            $table->boolean('type')->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('emp_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['uid']);
            $table->dropForeign(['emp_id']);
        });

        Schema::dropIfExists('attendances');
    }
}
