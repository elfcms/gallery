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
        Schema::table('gallery_settings', function (Blueprint $table) {
            $table->integer('watermark_indent_v')->length(4)->nullable()->default(0)->after('watermark');
            $table->integer('watermark_indent_h')->length(4)->nullable()->default(0)->after('watermark');
            $table->integer('watermark_size')->length(4)->nullable()->default(50)->after('watermark');
            $table->string('watermark_position')->nullable()->default('center,center')->after('watermark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_settings', function (Blueprint $table) {
            $table->dropColumn('watermark_indent_v');
            $table->dropColumn('watermark_indent_h');
            $table->dropColumn('watermark_size');
            $table->dropColumn('watermark_position');
        });
    }
};
