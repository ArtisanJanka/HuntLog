<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('gallery_items', function (Blueprint $table) {
        $table->id();
        $table->string('title')->nullable(); // optional title
        $table->string('image_path');        // uploaded file path
        $table->string('link')->nullable();  // optional external link
        $table->foreignId('hunting_type_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_items');
    }
};
