<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'cms_db';

    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();

            // Tags are also tenant-specific!
            $table->foreignId('tenant_id')
                ->constrained(config('database.connections.platform_db.database') . '.tenants');

            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']); // A tenant can't have duplicate slugs
        });

        Schema::create('content_item_tag', function (Blueprint $table) {
            $table->foreignId('content_item_id')->constrained('content_items')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->primary(['content_item_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_item_tag');
        Schema::dropIfExists('tags');
    }
};