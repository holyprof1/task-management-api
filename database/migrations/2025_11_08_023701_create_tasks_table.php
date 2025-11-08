<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // Ownership
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Core fields
            $table->string('title');
            $table->text('description')->nullable();

            // Allowed statuses: pending | in-progress | completed
            $table->enum('status', ['pending', 'in-progress', 'completed'])
                  ->default('pending')
                  ->index();

            $table->timestamps();

            // Helpful index
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
