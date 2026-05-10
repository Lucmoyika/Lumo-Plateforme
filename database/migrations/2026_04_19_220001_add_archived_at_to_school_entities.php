<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable()->after('status');
            $table->index(['school_id', 'academic_year', 'archived_at'], 'school_classes_year_archive_idx');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable()->after('status');
            $table->index(['school_id', 'archived_at'], 'students_school_archive_idx');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable()->after('status');
            $table->index(['school_id', 'archived_at'], 'teachers_school_archive_idx');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable()->after('color');
            $table->index(['class_id', 'archived_at'], 'schedules_class_archive_idx');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex('schedules_class_archive_idx');
            $table->dropColumn('archived_at');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropIndex('teachers_school_archive_idx');
            $table->dropColumn('archived_at');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_school_archive_idx');
            $table->dropColumn('archived_at');
        });

        Schema::table('school_classes', function (Blueprint $table) {
            $table->dropIndex('school_classes_year_archive_idx');
            $table->dropColumn('archived_at');
        });
    }
};
