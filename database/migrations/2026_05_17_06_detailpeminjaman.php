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
        Schema::create('detail_peminjamans', function (Blueprint $table) {
            $table->id('idDetailPeminjaman');
            
            $table->foreignId('idPeminjaman')->constrained('peminjamans', 'idPeminjaman')->onDelete('cascade');
            $table->foreignId('idBuku')->constrained('bukus', 'idBuku')->onDelete('cascade');
            
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjamans');
    }
};
