// database/migrations/2025_09_26_100002_create_appointments_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');
            $table->foreignId('availability_slot_id')->constrained()->onDelete('cascade');
            $table->dateTime('scheduled_time');
            $table->string('status')->default('scheduled'); // scheduled, completed, canceled, rescheduled
            $table->string('meeting_link')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
