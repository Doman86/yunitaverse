<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Single-row profile (id=1) for Yunita Dwi Alung.
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Yunita Dwi Alung');
            $table->string('title')->nullable();
            $table->text('bio')->nullable();
            $table->text('about')->nullable();
            $table->json('favorites')->nullable();
            $table->string('quote')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('moments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->date('date')->nullable();
            $table->string('category')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('caption')->nullable();
            $table->text('content');
            $table->enum('mood', ['good', 'normal', 'sad', 'all'])->default('all');
            $table->string('image')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });

        Schema::create('memories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('caption')->nullable();
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });

        Schema::create('journeys', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->year('year')->nullable();
            $table->date('date')->nullable();
            $table->string('category')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->string('image')->nullable();
            $table->string('category')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });

        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('category', ['music', 'food', 'movies', 'books', 'places', 'hobbies', 'things'])->default('things');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('journeys');
        Schema::dropIfExists('memories');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('moments');
        Schema::dropIfExists('profiles');
    }
};
