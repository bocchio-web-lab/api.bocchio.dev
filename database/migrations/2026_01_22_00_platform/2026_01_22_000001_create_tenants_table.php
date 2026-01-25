<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'platform_db';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->unsignedBigInteger('owner_id'); // References identity_db.users.id
            $table->string('name');
            $table->string('public_slug')->unique();
            $table->enum('access_level', ['private', 'public', 'token_protected'])->default('private');
            $table->string('public_api_key')->nullable()->unique();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index(['service_id', 'owner_id']);
            $table->index('public_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('tenants');
    }
};
