<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psy_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('psychologist_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->enum('status', ['booked', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('booked');
            $table->text('notes')->nullable(); // Public notes visible to patient
            $table->decimal('session_fee', 8, 2)->nullable();
            $table->timestamps();

            // Ensure no double booking
            $table->unique(['psychologist_id', 'start_time']);
            
            // Index for efficient queries
            $table->index(['psychologist_id', 'status']);
            $table->index(['patient_id', 'status']);
            $table->index('start_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psy_sessions');
    }
};

