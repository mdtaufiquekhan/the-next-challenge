<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('cover_image')->nullable();
            $table->text('review_challenge')->nullable();
            $table->text('review_solution')->nullable();
            $table->text('review_submission')->nullable();
            $table->text('review_evaluation')->nullable();
            $table->text('review_participation')->nullable();
            $table->text('review_awards')->nullable();
            $table->text('review_deadline')->nullable();
            $table->text('review_resources')->nullable();
            $table->string('status')->default('published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};