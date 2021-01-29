<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDeviceIdToUserTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_tokens', function (Blueprint $table) {
            $table->string('device_id')->nullable()->after('token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('user_tokens','device_id'))
        {
            Schema::table('user_tokens', function (Blueprint $table) {
                $table->dropColumn('device_id');
            });
        }
    }
}
