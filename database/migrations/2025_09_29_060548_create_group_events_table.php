<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('group_events', function (Blueprint $table) {
            $table->id();

            // required: relate event to a leader's group
            $table->foreignId('group_id')
                  ->constrained('groups')
                  ->cascadeOnDelete();

            // optional: link to a saved polygon
            $table->foreignId('polygon_id')
                  ->nullable()
                  ->constrained('polygons')
                  ->nullOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();

            $table->string('meetup_location')->nullable();

            // who created it (useful for auditing)
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // visibility within app – keep simple for now
            $table->enum('visibility', ['group'])->default('group');

            $table->timestamps();

            // handy indexes
            $table->index(['group_id', 'start_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_events');
    }
};
