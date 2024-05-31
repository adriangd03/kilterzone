<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_ruta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->mediumText('ruta');
            $table->string('nom_ruta')->unique();
            $table->string('descripcio');
            $table->string('dificultat');
            $table->string('inclinacio');
            $table->string('layout');
            $table->integer('likes')->default(0);
            $table->integer('escalada')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ruta');
    }
};
