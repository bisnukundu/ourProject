<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("full_name");
            $table->string("user_name")->unique();
            $table->string('email')->unique();
            $table->string("phone");
            $table->boolean("status")->default(false);
            $table->string("sponserId")->nullable();
            $table->string("referral_link")->nullable();
            $table->string('profile_image')->nullable();
            $table->integer('active_balance')->default(0);
            $table->integer('income_balance')->default(0);
            $table->integer('bonus_balance')->default(0);
            $table->integer('shoping_balance')->default(0);
            $table->boolean('isAgent')->default(false);
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
