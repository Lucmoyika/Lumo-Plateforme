<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('schools')) {
            return;
        }

        // Ensure column exists on legacy databases before migration.
        if (!Schema::hasColumn('schools', 'level_types')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->json('level_types')->nullable()->after('name');
            });
        }

        if (Schema::hasColumn('schools', 'type') && Schema::hasColumn('schools', 'level_types')) {
            $typeMap = [
                'primary' => 'primaire',
                'middle' => 'secondaire',
                'secondary' => 'secondaire',
                'high' => 'humanites',
                'technical' => 'humanites',
                'private' => 'humanites',
                'maternelle' => 'maternelle',
                'primaire' => 'primaire',
                'secondaire' => 'secondaire',
                'humanites' => 'humanites',
            ];

            $schools = DB::table('schools')->select('id', 'type', 'level_types')->get();

            foreach ($schools as $school) {
                $existingLevels = [];
                if (!empty($school->level_types)) {
                    $decoded = json_decode((string) $school->level_types, true);
                    if (is_array($decoded)) {
                        $existingLevels = $decoded;
                    }
                }

                if (!empty($existingLevels) || empty($school->type)) {
                    continue;
                }

                $key = strtolower(trim((string) $school->type));
                $normalized = $typeMap[$key] ?? $key;

                DB::table('schools')
                    ->where('id', $school->id)
                    ->update(['level_types' => json_encode([$normalized])]);
            }
        }

        // Normalize null values to empty array.
        if (Schema::hasColumn('schools', 'level_types')) {
            DB::table('schools')
                ->whereNull('level_types')
                ->update(['level_types' => json_encode([])]);
        }

        if (Schema::hasColumn('schools', 'type')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('schools')) {
            return;
        }

        if (!Schema::hasColumn('schools', 'type')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->string('type')->nullable()->after('name');
            });
        }

        if (Schema::hasColumn('schools', 'level_types') && Schema::hasColumn('schools', 'type')) {
            $schools = DB::table('schools')->select('id', 'level_types', 'type')->get();

            foreach ($schools as $school) {
                if (!empty($school->type)) {
                    continue;
                }

                $decoded = json_decode((string) ($school->level_types ?? ''), true);
                if (is_array($decoded) && !empty($decoded[0])) {
                    DB::table('schools')
                        ->where('id', $school->id)
                        ->update(['type' => (string) $decoded[0]]);
                }
            }
        }
    }
};
