<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->unsignedBigInteger('nationality_id')->nullable()->after('full_name');
            $table->unsignedBigInteger('country')->nullable()->after('nationality_id');
            $table->unsignedBigInteger('region')->nullable()->after('country');
            $table->unsignedBigInteger('city')->nullable()->after('region');

            $table->foreign('nationality_id')->references('id')->on('nationalities')->onDelete('set null');
            $table->foreign('country')->references('id')->on('countries')->onDelete('set null')->onUpdate('restrict');
            $table->foreign('region')->references('id')->on('regions')->onDelete('set null');
            $table->foreign('city')->references('id')->on('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['nationality_id']);
            $table->dropForeign(['country']);
            $table->dropForeign(['region']);
            $table->dropForeign(['city']);

            $table->dropColumn(['nationality_id', 'country', 'region', 'city']);

        });
    }
};
