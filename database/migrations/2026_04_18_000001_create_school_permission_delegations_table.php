<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_permission_delegations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('granted_by')->constrained('users')->cascadeOnDelete();
            $table->string('role_name');
            $table->json('permissions');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->text('notes')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'user_id']);
            $table->index(['school_id', 'starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_permission_delegations');
    }
};