<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDownReasonToWebsiteLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('website_logs', function (Blueprint $table) {
            $table->string('down_reason')->nullable()->after('up_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('website_logs','down_reason'))
        {
            Schema::table('website_logs', function (Blueprint $table) {
                $table->dropColumn('down_reason');
            });
        }
      
    }
}
