<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDownImageUrlToWebsiteLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('website_logs', function (Blueprint $table) {
            $table->string('down_image_url')->nullable()->after('down_reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('website_logs','down_image_url'))
        {
            Schema::table('website_logs', function (Blueprint $table) {
                $table->dropColumn('down_image_url');
            });
        }
    }
}
