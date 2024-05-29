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
        Schema::create('user_comentari', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ruta_id');
            $table->string('comentari');
            $table->integer('likes')->default(0);
            $table->unsignedBigInteger('comentari_id')->nullable();
            $table->boolean('editat')->default(false);
            $table->boolean('esborrat')->default(false);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ruta_id')->references('id')->on('user_ruta')->onDelete('cascade');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_comentari');
    }
};
