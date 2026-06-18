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
        Schema::create('genre_bukus', function (Blueprint $table) {
            $table->unsignedBigInteger('idGenre');
            $table->unsignedBigInteger('idBuku');
            $table->timestamps();

            $table->primary(['idBuku', 'idGenre']);

            $table->foreign('idGenre')->references('idGenre')->on('genres')->onDelete('cascade');
            $table->foreign('idBuku')->references('idBuku')->on('bukus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_bukus');
    }
};
