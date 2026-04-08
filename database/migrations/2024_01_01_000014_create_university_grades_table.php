<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('university_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('university_students')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('academic_year');
            $table->unsignedTinyInteger('semester');
            $table->decimal('score', 5, 2);
            $table->decimal('max_score', 5, 2)->default(20);
            $table->unsignedInteger('credits_obtained')->default(0);
            $table->enum('status', ['validé', 'non_validé'])->default('non_validé');
            $table->unsignedTinyInteger('exam_session')->default(1)->comment('1 or 2');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_grades');
    }
};
