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
        Schema::connection($this->connection)->create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->foreignId('content_item_id')->constrained('content_items')->onDelete('cascade');
            $table->unsignedBigInteger('author_id')->nullable(); // FK to identity_db.users (null for guests)
            $table->string('author_name')->nullable(); // For guest comments
            $table->text('body');
            $table->boolean('approved')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('content_item_id');
            $table->index('author_id');
            $table->index(['tenant_id', 'approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('comments');
    }
};
