<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username')->unique();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('password_changed_at')->nullable();
            $table->string('user_icon_url')->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->integer('age')->nullable();
            $table->string('nationality')->nullable();
            $table->string('timezone')->nullable();
            $table->enum('language', ['en', 'tc', 'sc'])->default('en');
            $table->timestamp('last_login_at')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('one_time_passcode', function (Blueprint $table) {
            $table->id('otp_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['forgot_password', 'email_verification'])->nullable();
            $table->string('email')->nullable();
            $table->string('passcode');
            $table->boolean('is_verified')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('user_id')->on('users');
        });

        Schema::create('report', function (Blueprint $table) {
            $table->id('report_id');
            $table->unsignedBigInteger('user_id');
            $table->string('product');
            $table->string('shop');
            $table->string('category')->nullable();
            $table->string('district')->nullable();
            $table->string('sub-district')->nullable();
            $table->timestamp('datetime')->nullable();
            $table->string('Address')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('description')->nullable();
            $table->softDeletes();

            $table->foreign('user_id')->references('user_id')->on('users');
        });

        Schema::create('report_file', function (Blueprint $table) {
            $table->id('report_file_id');
            $table->unsignedBigInteger('report_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('report_id')->references('report_id')->on('report');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_file');
        Schema::dropIfExists('report');
        Schema::dropIfExists('one_time_passcode');
        Schema::dropIfExists('users');
    }
};
