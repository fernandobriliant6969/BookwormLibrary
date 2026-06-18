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
        Schema::create('review_bukus', function (Blueprint $table) {
            $table->id('idReview');

            $table->foreignId('idUser')->constrained('users', 'idUser')->onDelete('cascade');
            $table->foreignId('idBuku')->constrained('bukus', 'idBuku')->onDelete('cascade');
            $table->text('pesan');
            $table->integer('rating');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
