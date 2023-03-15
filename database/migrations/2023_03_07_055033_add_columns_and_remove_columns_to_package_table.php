<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsAndRemoveColumnsToPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('group_tag');
            $table->dropColumn('duration_in_days');
            $table->dropColumn('is_disabled');
            $table->dropColumn('price_id');
            $table->string('description')->after('name')->nullable();
            $table->string('type')->after('description')->nullable();
            $table->string('status')->after('type')->default(1);
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
            $table->string('group_tag')->nullable();
            $table->integer('duration_in_days')->default(0);
            $table->boolean('is_disabled')->default(0);
            $table->string('price_id')->nullable();
            $table->dropColumn(['description']);
            $table->dropColumn(['type']);
            $table->dropColumn(['status']);
        });
    }
}
