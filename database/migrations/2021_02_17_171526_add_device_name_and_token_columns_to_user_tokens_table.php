<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceNameAndTokenColumnsToUserTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_tokens', function (Blueprint $table) {
            $table->string('device_name')->nullable()->after('device_id');
            $table->text('jwt_token')->nullable()->after('device_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumns('user_tokens',['device_name','jwt_token']))
        {
            Schema::table('user_tokens', function (Blueprint $table) {
                $table->dropColumn('device_name');
                $table->dropColumn('jwt_token');
            });
        }
    }
}
