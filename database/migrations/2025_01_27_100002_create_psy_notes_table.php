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
        Schema::create('psy_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('psy_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('psychologist_id')->constrained()->onDelete('cascade');
            $table->text('content'); // Encrypted sensitive notes
            $table->string('note_type')->default('session_notes'); // session_notes, follow_up, assessment, etc.
            $table->boolean('is_encrypted')->default(true);
            $table->timestamps();

            // Index for efficient queries
            $table->index(['psy_session_id', 'psychologist_id']);
            $table->index('note_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psy_notes');
    }
};

