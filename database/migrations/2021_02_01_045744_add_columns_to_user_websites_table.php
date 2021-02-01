<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUserWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_websites', function (Blueprint $table) {
            $table->string('developer_email')->nullable()->after('emails');
            $table->string('owner_email')->nullable()->after('developer_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumns('user_websites','developer_email','owner_email'))
            Schema::table('user_websites', function (Blueprint $table) {
                $table->dropColumn('developer_email');
                $table->dropColumn('owner_email');
        });
    }
}
