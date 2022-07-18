<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripePayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->default(NULL)->constrained('users');
            $table->foreignId('package_id')->nullable()->default(NULL)->constrained('packages');
            $table->string('currency')->nullable();
            $table->string('detail')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('subscription_id')->nullable();
            $table->boolean('is_subscribed')->default(false);
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
        Schema::dropIfExists('stripe_payment');
    }
}
