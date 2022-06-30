<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWebsitePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_website_permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('website_id');
            $table->integer('permission')->comment = '0 = read, 1 = read/write';
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_website_permissions');
    }
}
