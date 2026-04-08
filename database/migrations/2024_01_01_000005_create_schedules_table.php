<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->string('subject');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->tinyInteger('day_of_week')->comment('1=Monday, 7=Sunday');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->string('color', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
