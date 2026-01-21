<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'cms_db';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->create('content_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id'); // FK to platform_db.tenants
            $table->unsignedBigInteger('author_id'); // FK to identity_db.users
            $table->enum('type', ['post', 'page', 'project'])->default('post');
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('author_id');
            $table->index(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'type']);
            $table->index('published_at');

            // Unique slug per tenant
            $table->unique(['tenant_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('content_items');
    }
};
