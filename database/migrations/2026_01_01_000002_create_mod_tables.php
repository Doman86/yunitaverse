<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mod_things_to_do', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->enum('mood', ['good', 'normal', 'sad', 'all'])->default('all');
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->date('date')->nullable();
            $table->string('category')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('mod_notes', function (Blueprint $table) {
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

        Schema::create('mod_photos', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('title')->nullable();
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->enum('mood', ['good', 'normal', 'sad', 'all'])->default('all');
            $table->date('date')->nullable();
            $table->string('category')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });

        Schema::create('mod_playlists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->string('spotify_url');
            $table->enum('mood', ['good', 'normal', 'sad', 'all'])->default('all');
            $table->string('category')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });

        Schema::create('mod_surprises', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['photo', 'note', 'quote', 'activity', 'music', 'memory']);
            $table->string('title')->nullable();
            $table->string('caption')->nullable();
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->enum('mood', ['good', 'normal', 'sad', 'all'])->default('all');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });

        Schema::create('soundtracks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->string('spotify_url');
            $table->enum('kind', ['playlist', 'track'])->default('playlist');
            $table->string('category')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('soundtracks');
        Schema::dropIfExists('mod_surprises');
        Schema::dropIfExists('mod_playlists');
        Schema::dropIfExists('mod_photos');
        Schema::dropIfExists('mod_notes');
        Schema::dropIfExists('mod_things_to_do');
    }
};
