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
            $table->text('two_factor_last_challenged')->nullable();
            $table->json('two_factor')->after('two_factor_locked')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('two_factor');
            $table->dropColumn('two_factor_last_challenged');
            $table->dropColumn('two_factor_locked');
            $table->dropColumn('two_factor_completed');
            $table->dropColumn('two_factor_confirmed_at');
            $table->dropColumn('two_factor_recovery_codes');
            $table->dropColumn('two_factor_secret');
        });
    }
};
