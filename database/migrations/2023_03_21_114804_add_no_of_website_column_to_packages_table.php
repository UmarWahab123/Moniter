<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoOfWebsiteColumnToPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('no_of_servers')->after('type')->nullable();
            $table->integer('no_of_websites')->after('no_of_servers')->nullable();
            $table->integer('no_of_users')->after('no_of_websites')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('no_of_servers');
            $table->dropColumn('no_of_websites');
            $table->dropColumn('no_of_users');
        });
    }
}
