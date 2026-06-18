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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id('idPeminjaman');
            $table->unsignedBigInteger('idUser');
            $table->foreign('idUser')->references('idUser')->on('users')->onDelete('cascade');
            $table->date('tanggalPeminjaman');
            $table->date('tanggalKembali');
            $table->integer('lamaPinjam');
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
