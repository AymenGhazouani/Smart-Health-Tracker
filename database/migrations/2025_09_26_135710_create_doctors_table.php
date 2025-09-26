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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        $table->string('email')->unique();
        $table->string('phone')->nullable();
        $table->foreignId('specialty_id')->constrained()->cascadeOnDelete(); // links to specialties
        $table->string('photo')->nullable(); // store image filename or URL
        $table->decimal('rating', 3, 2)->default(0); // average rating
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
};