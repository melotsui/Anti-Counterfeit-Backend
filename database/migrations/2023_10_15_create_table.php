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
            $table->string('name')->nullable();
            $table->string('email')->unique();
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
        
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('category_name');
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id('district_id');
            $table->string('district_name');
        });

        Schema::create('sub_districts', function (Blueprint $table) {
            $table->id('sub_district_id');
            $table->string('sub_district_name');
            $table->unsignedBigInteger('district_id');
            $table->foreign('district_id')->references('district_id')->on('districts');
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->unsignedBigInteger('user_id');
            $table->string('product');
            $table->string('shop');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('sub_district_id')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('category_id')->on('categories');
            $table->foreign('district_id')->references('district_id')->on('districts');
            $table->foreign('sub_district_id')->references('sub_district_id')->on('sub_districts');
            $table->foreign('user_id')->references('user_id')->on('users');
        });

        Schema::create('report_files', function (Blueprint $table) {
            $table->id('report_file_id');
            $table->unsignedBigInteger('report_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('report_id')->references('report_id')->on('reports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_files');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('sub_districts');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('one_time_passcode');
        Schema::dropIfExists('users');
    }
};
