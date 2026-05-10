<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter les colonnes à la table schools
        Schema::table('schools', function (Blueprint $table) {
            if (!Schema::hasColumn('schools', 'education_submodule')) {
                $table->string('education_submodule')->nullable()->after('level_types')
                    ->comment('Submodule key: mp (Maternelle&Primaire), ps (Primaire&Secondaire), sh (Secondaire&Humanités), full');
            }
        });

        // Ajouter les colonnes à la table teachers
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'gender')) {
                $table->enum('gender', ['M', 'F'])->nullable()->after('experience_years')
                    ->comment('Teacher gender - for Maternelle, only F is allowed');
            }
            if (!Schema::hasColumn('teachers', 'contract_type')) {
                $table->enum('contract_type', ['annual', 'semester', 'temporary'])->default('annual')->after('gender')
                    ->comment('Contract type: annual (full year), semester, temporary');
            }
            if (!Schema::hasColumn('teachers', 'role')) {
                $table->enum('role', ['teacher', 'assistant', 'substitute'])->default('teacher')->after('contract_type')
                    ->comment('Role: teacher (main), assistant (aide), substitute (remplaçant)');
            }
        });

        // Ajouter les colonnes à la table school_classes
        Schema::table('school_classes', function (Blueprint $table) {
            if (!Schema::hasColumn('school_classes', 'class_variant')) {
                $table->string('class_variant')->nullable()->after('level')
                    ->comment('Class variant for multiple classes per level (A, B, C, etc.)');
            }
            if (!Schema::hasColumn('school_classes', 'education_submodule')) {
                $table->string('education_submodule')->nullable()->after('class_variant')
                    ->comment('Copy of school submodule for denormalization: mp, ps, sh, full');
            }
            if (!Schema::hasColumn('school_classes', 'archived_at')) {
                $table->softDeletes('archived_at')->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (Schema::hasColumn('schools', 'education_submodule')) {
                $table->dropColumn('education_submodule');
            }
        });

        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'gender')) {
                $table->dropColumn('gender');
            }
            if (Schema::hasColumn('teachers', 'contract_type')) {
                $table->dropColumn('contract_type');
            }
            if (Schema::hasColumn('teachers', 'role')) {
                $table->dropColumn('role');
            }
        });

        Schema::table('school_classes', function (Blueprint $table) {
            if (Schema::hasColumn('school_classes', 'class_variant')) {
                $table->dropColumn('class_variant');
            }
            if (Schema::hasColumn('school_classes', 'education_submodule')) {
                $table->dropColumn('education_submodule');
            }
            if (Schema::hasColumn('school_classes', 'archived_at')) {
                $table->dropColumn('archived_at');
            }
        });
    }
};
