<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEndDateToLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::table('leaves', function (Blueprint $table) {
        $table->date('end_date')->nullable()->after('leave_date');
    });
}

public function down()
{
    Schema::table('leaves', function (Blueprint $table) {
        $table->dropColumn('end_date');
    });
}


}
