<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->morphs('payable');
            $table->enum('method', ['mobile_money', 'card', 'wallet', 'bank'])->default('mobile_money');
            $table->string('provider')->nullable();
            $table->string('transaction_id')->nullable()->unique();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('CDF');
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->json('gateway_response')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
