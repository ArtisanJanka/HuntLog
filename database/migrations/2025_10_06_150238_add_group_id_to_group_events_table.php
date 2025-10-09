<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add missing columns safely
        Schema::table('group_events', function (Blueprint $table) {
            // group_id
            if (!Schema::hasColumn('group_events', 'group_id')) {
                $table->foreignId('group_id')
                    ->after('id')
                    ->constrained('groups')
                    ->cascadeOnDelete();
            }

            // If your table didn't have scheduling columns, add them
            if (!Schema::hasColumn('group_events', 'start_at')) {
                // place these after an existing column if you want; 'after' is optional
                $table->dateTime('start_at')->nullable();
            }
            if (!Schema::hasColumn('group_events', 'end_at')) {
                $table->dateTime('end_at')->nullable();
            }

            // Optional fields you might want (only if missing)
            if (!Schema::hasColumn('group_events', 'title')) {
                $table->string('title')->nullable();
            }
            if (!Schema::hasColumn('group_events', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('group_events', 'polygon_id')) {
                $table->foreignId('polygon_id')->nullable()->constrained('polygons')->nullOnDelete();
            }
            if (!Schema::hasColumn('group_events', 'meetup_location')) {
                $table->string('meetup_location')->nullable();
            }
            if (!Schema::hasColumn('group_events', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('group_events', 'visibility')) {
                $table->enum('visibility', ['group'])->default('group');
            }
        });

        // Add the composite index only if both columns exist
        if (Schema::hasColumn('group_events', 'group_id') && Schema::hasColumn('group_events', 'start_at')) {
            Schema::table('group_events', function (Blueprint $table) {
                // name the index so we can drop it later if needed
                $table->index(['group_id', 'start_at'], 'group_events_group_id_start_at_index');
            });
        }
    }

    public function down(): void
    {
        // Drop the composite index if it exists
        if (Schema::hasColumn('group_events', 'group_id') && Schema::hasColumn('group_events', 'start_at')) {
            Schema::table('group_events', function (Blueprint $table) {
                // If this throws because the index name differs, just comment it out
                $table->dropIndex('group_events_group_id_start_at_index');
            });
        }

        Schema::table('group_events', function (Blueprint $table) {
            // Drop foreign keys + columns if they exist
            if (Schema::hasColumn('group_events', 'group_id')) {
                $table->dropConstrainedForeignId('group_id');
            }
            if (Schema::hasColumn('group_events', 'polygon_id')) {
                $table->dropConstrainedForeignId('polygon_id');
            }
            if (Schema::hasColumn('group_events', 'created_by')) {
                $table->dropConstrainedForeignId('created_by');
            }

            // Optional: only drop the extra columns if you *really* want to revert them
            if (Schema::hasColumn('group_events', 'start_at')) $table->dropColumn('start_at');
            if (Schema::hasColumn('group_events', 'end_at'))   $table->dropColumn('end_at');
            if (Schema::hasColumn('group_events', 'title'))    $table->dropColumn('title');
            if (Schema::hasColumn('group_events', 'description')) $table->dropColumn('description');
            if (Schema::hasColumn('group_events', 'meetup_location')) $table->dropColumn('meetup_location');
            if (Schema::hasColumn('group_events', 'visibility')) $table->dropColumn('visibility');
        });
    }
};
