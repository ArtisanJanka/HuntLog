<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('group_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('group_requests', 'group_id')) {
                $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade')->after('user_id');
            }
            if (!Schema::hasColumn('group_requests', 'note')) {
                $table->text('note')->nullable()->after('group_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('group_requests', function (Blueprint $table) {
            if (Schema::hasColumn('group_requests', 'group_id')) {
                $table->dropConstrainedForeignId('group_id');
            }
            if (Schema::hasColumn('group_requests', 'note')) {
                $table->dropColumn('note');
            }
        });
    }
};

