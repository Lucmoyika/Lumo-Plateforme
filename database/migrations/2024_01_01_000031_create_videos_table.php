<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->string('sub_module')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('youtube_url');
            $table->string('thumbnail')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 10)->default('CDF');
            $table->unsignedInteger('duration')->nullable()->comment('Duration in seconds');
            $table->string('status')->default('active');
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
