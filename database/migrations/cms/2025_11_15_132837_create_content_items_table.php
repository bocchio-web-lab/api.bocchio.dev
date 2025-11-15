<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * The database connection that should be used by the migration.
     *
     * @var string
     */
    protected $connection = 'cms_db';

    public function up(): void
    {
        Schema::create('content_items', function (Blueprint $table) {
            $table->id();

            // Foreign key to platform.tenants
            $table->foreignId('tenant_id')
                ->constrained(config('database.connections.platform_db.database') . '.tenants');

            // Foreign key to identity.users
            $table->foreignId('author_id')
                ->constrained(config('database.connections.identity_db.database') . '.users');

            $table->string('type')->default('post'); // e.g., 'post', 'project', 'page'
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('body')->nullable();
            $table->text('excerpt')->nullable();
            $table->json('meta')->nullable(); // For extra fields

            $table->string('status')->default('draft'); // 'draft', 'published', 'archived'
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_items');
    }
};