<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiryDateToUserWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_websites', function (Blueprint $table) {
            $table->date('domain_expiry_date')->nullable()->after('owner_email');
            $table->string('domain_registrar')->nullable()->after('domain_expiry_date');
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
            $table->dropColumn(['domain_expiry_date']);
            $table->dropColumn(['domain_registrar']);
        });
    }
}
