<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('owner')->after('password');
            $table->string('status')->default('active')->after('role');

            $table->string('plan_name')->default('Free')->after('status');
            $table->string('subscription_status')->default('active')->after('plan_name');
            $table->timestamp('subscription_started_at')->nullable()->after('subscription_status');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_started_at');

            $table->timestamp('last_login_at')->nullable()->after('subscription_ends_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'status',
                'plan_name',
                'subscription_status',
                'subscription_started_at',
                'subscription_ends_at',
                'last_login_at',
            ]);
        });
    }
};