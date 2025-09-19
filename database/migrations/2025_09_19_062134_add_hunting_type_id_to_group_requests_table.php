<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('group_requests', function (Blueprint $table) {
        $table->unsignedBigInteger('hunting_type_id')->nullable()->after('user_id'); // nullable for now
    });
}


public function down()
{
    Schema::table('group_requests', function (Blueprint $table) {
        $table->dropColumn('hunting_type_id');
    });
}

};
