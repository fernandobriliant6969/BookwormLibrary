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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id('idBuku');
            $table->string('judul');
            $table->string('pengarang');
            $table->string('penerbit');
            $table->date('tanggalTerbit');
            $table->integer('jumlahHalaman');
            $table->string('photoUrl')->nullable();
            $table->integer('stok');
            $table->string('status');
            $table->text('ringkasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
