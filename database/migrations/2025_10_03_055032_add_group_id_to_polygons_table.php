<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('polygons', function (Blueprint $table) {
            // nullable at first so existing rows don't break
            $table->foreignId('group_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('polygons', function (Blueprint $table) {
            $table->dropConstrainedForeignId('group_id'); // drops FK + column name
        });
    }
};

