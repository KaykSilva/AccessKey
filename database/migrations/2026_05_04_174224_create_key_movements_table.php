<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('key_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('key_id')
                ->constrained('keys')
                ->cascadeOnDelete();

            $table->foreignId('person_id')
                ->constrained('key_people')
                ->cascadeOnDelete();

            $table->foreignId('taken_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('delivered_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('received_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('taken_at')->nullable();
            $table->dateTime('returned_at')->nullable();

            $table->text('take_notes')->nullable();
            $table->text('return_notes')->nullable();

            $table->enum('status', [
                'borrowed',
                'returned',
                'late',
            ])->default('borrowed');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('key_movements');
    }
};
