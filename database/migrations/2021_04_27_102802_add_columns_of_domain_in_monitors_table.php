<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsOfDomainInMonitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitors', function (Blueprint $table) {
            $table->date('domain_creation_date')->nullable()->after('certificate_expiration_date');
            $table->date('domain_expiry_date')->nullable()->after('domain_creation_date');
            $table->date('domain_updated_date')->nullable()->after('domain_expiry_date');
            $table->date('domain_expiring_in_days')->nullable()->after('domain_updated_date');
            $table->tinyInteger('domain_checked')->after('domain_expiring_in_days')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monitors', function (Blueprint $table) {
            $table->dropColumn('domain_creation_date');
            $table->dropColumn('domain_expiry_date');
            $table->dropColumn('domain_updated_date');
            $table->dropColumn('domain_expiring_in_days');
            $table->dropColumn('domain_checked');
        });
    }
}
