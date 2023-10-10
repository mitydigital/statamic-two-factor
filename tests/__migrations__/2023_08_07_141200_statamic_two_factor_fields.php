<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('two_factor_secret')->after('remember_token')->nullable();
            $table->text('two_factor_recovery_codes')->after('two_factor_secret')->nullable();
            $table->timestamp('two_factor_confirmed_at')->after('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_completed')->after('two_factor_confirmed_at')->nullable();
            $table->boolean('two_factor_locked')->after('two_factor_completed')->default(false);
            $table->string('two_factor_last_challenged', 100)->nullable();
            $table->json('two_factor')->after('two_factor_locked')->nullable();
        });
    }
};
