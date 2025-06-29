<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('fourth_name')->nullable();

            $table->string('latest_name')->nullable();

            $table->string('full_name')->nullable();

            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('mobile_country_code')->nullable();
            $table->string('mobile_number')->unique()->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();

            $table->string('gender')->nullable();
            $table->text('address')->nullable();

            $table->string('referral_code')->unique()->nullable();
            $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete();

            $table->unsignedTinyInteger('role')->nullable();

            $table->boolean('is_old')->default(false);

            $table->longText('token')->nullable();
            $table->string('photo')->nullable();

            $table->timestamp('last_login')->nullable();
            $table->ipAddress('ip')->nullable();

            $table->string('password');

            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('long', 10, 7)->nullable();

            $table->string('is_active')->default(0);

            $table->json('json1')->nullable();
            $table->json('json2')->nullable();
            $table->json('json3')->nullable();
            $table->json('json4')->nullable();
            $table->json('json5')->nullable();

            $table->string('column1')->nullable();
            $table->string('column2')->nullable();
            $table->string('column3')->nullable();
            $table->string('column4')->nullable();
            $table->string('column5')->nullable();

            $table->longText('longtext1')->nullable();
            $table->longText('longtext2')->nullable();
            $table->longText('longtext3')->nullable();
            $table->longText('longtext4')->nullable();
            $table->longText('longtext5')->nullable();

            $table->rememberToken();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
