<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'cms_db';

    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('content_item_id')->constrained('content_items')->cascadeOnDelete();

            // Nullable foreign key to identity.users (for logged-in commenters)
            $table->foreignId('author_id')->nullable()
                ->constrained(config('database.connections.identity_db.database') . '.users');

            $table->string('author_name')->nullable(); // For guest commenters
            $table->string('author_email')->nullable(); // For guest commenters

            $table->text('body');
            $table->boolean('approved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};