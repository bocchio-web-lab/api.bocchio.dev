<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'platform_db';

    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();

            // Foreign key to platform.services
            $table->foreignId('service_id')->constrained('services');

            // Foreign key to identity.users
            // We specify the DB.table for the constraint
            $table->foreignId('owner_id')
                ->constrained(config('database.connections.identity_db.database') . '.users');

            $table->string('name'); // e.g., "My Personal Blog"

            // Public identifier
            $table->string('public_slug')->nullable()->unique();

            // Access control
            $table->enum('access_level', ['private', 'public', 'token_protected'])
                ->default('private');

            $table->string('public_api_key')->nullable()->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};