<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('key_reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('key_id')
                ->constrained('keys')
                ->cascadeOnDelete();

            $table->foreignId('person_id')
                ->constrained('key_people')
                ->cascadeOnDelete();

            $table->foreignId('requested_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('start_at');
            $table->dateTime('end_at');

            $table->enum('period', [
                'morning',
                'afternoon',
                'night',
                'custom',
            ])->default('custom');

            $table->text('reason')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'cancelled',
                'finished',
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('key_reservations');
    }
};
