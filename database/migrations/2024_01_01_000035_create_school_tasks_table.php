<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority', 20)->default('medium');
            $table->string('status', 20)->default('todo');
            $table->date('due_date')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'status']);
            $table->index(['school_id', 'assigned_to']);
            $table->index(['school_id', 'priority']);
            $table->index(['due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_tasks');
    }
};
