<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom pelengkap toko dibuat boleh kosong agar formulir pembuatan toko
     * cukup meminta nama, deskripsi, dan kota.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('logo')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('address_id')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('postal_code')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('logo')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('address_id')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->string('postal_code')->nullable(false)->change();
        });
    }
};
