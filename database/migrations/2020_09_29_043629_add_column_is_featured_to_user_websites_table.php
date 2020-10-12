<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsFeaturedToUserWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_websites', function (Blueprint $table) {
            $table->tinyInteger('is_featured')->nullable()->after('emails');
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
            $table->tinyInteger('is_featured');
        });
    }
}
