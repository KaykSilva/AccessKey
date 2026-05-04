<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keys', function (Blueprint $table) {
            $table->id();

            $table->foreignId('responsible_id')
                ->nullable()
                ->constrained('key_responsibles')
                ->nullOnDelete();

            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('location')->nullable();

            $table->enum('status', [
                'available',
                'borrowed',
                'maintenance',
                'inactive',
            ])->default('available');

            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keys');
    }
};
