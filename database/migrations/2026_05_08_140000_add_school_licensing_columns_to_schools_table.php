<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (!Schema::hasColumn('schools', 'education_submodule')) {
                $table->string('education_submodule', 20)->nullable()->after('level_types');
            }
            if (!Schema::hasColumn('schools', 'license_plan_code')) {
                $table->string('license_plan_code', 100)->nullable()->after('education_submodule');
            }
            if (!Schema::hasColumn('schools', 'subscription_status')) {
                $table->string('subscription_status', 20)->default('trial')->after('license_plan_code');
            }
            if (!Schema::hasColumn('schools', 'billing_duration_months')) {
                $table->unsignedSmallInteger('billing_duration_months')->nullable()->after('subscription_status');
            }
            if (!Schema::hasColumn('schools', 'license_price_cdf')) {
                $table->unsignedBigInteger('license_price_cdf')->nullable()->after('billing_duration_months');
            }
            if (!Schema::hasColumn('schools', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('license_price_cdf');
            }
            if (!Schema::hasColumn('schools', 'subscription_starts_at')) {
                $table->timestamp('subscription_starts_at')->nullable()->after('trial_ends_at');
            }
            if (!Schema::hasColumn('schools', 'subscription_ends_at')) {
                $table->timestamp('subscription_ends_at')->nullable()->after('subscription_starts_at');
            }
            if (!Schema::hasColumn('schools', 'mobile_access_enabled')) {
                $table->boolean('mobile_access_enabled')->default(true)->after('subscription_ends_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $columns = [
                'education_submodule',
                'license_plan_code',
                'subscription_status',
                'billing_duration_months',
                'license_price_cdf',
                'trial_ends_at',
                'subscription_starts_at',
                'subscription_ends_at',
                'mobile_access_enabled',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('schools', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
