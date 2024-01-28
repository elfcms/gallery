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
        Schema::create('gallery_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('image_file_size')->nullable()->default(1536);
            $table->boolean('is_preview')->nullable()->default(1);
            $table->integer('preview_file_size')->nullable()->default(768);
            $table->integer('preview_width')->nullable()->default(800);
            $table->integer('preview_heigt')->nullable()->default(800);
            $table->boolean('is_thumbnail')->nullable()->default(1);
            $table->integer('thumbnail_file_size')->nullable()->default(512);
            $table->integer('thumbnail_width')->nullable()->default(400);
            $table->integer('thumbnail_heigt')->nullable()->default(400);
            $table->boolean('is_watermark')->nullable()->default(0);
            $table->string('watermark')->nullable();
            $table->boolean('watermark_first')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_settings');
    }
};
