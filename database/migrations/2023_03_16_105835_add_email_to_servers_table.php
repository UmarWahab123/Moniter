<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailToServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->string('primary_email')->after('os')->nullable();
            $table->string('secondary_email')->after('primary_email')->nullable();
            $table->string('developer_email')->after('secondary_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn(['primary_email']);
            $table->dropColumn(['secondary_email']);
            $table->dropColumn(['developer_email']);
        });
    }
}
