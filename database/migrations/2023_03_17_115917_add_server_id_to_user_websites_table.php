<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServerIdToUserWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_websites', function (Blueprint $table) {
            $table->foreignId('server_id')->nullable()->after('id')->default(NULL)->constrained('servers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_websites', function (Blueprint $table) {
            $table->dropColumn(['server_id']);
        });
    }
}
