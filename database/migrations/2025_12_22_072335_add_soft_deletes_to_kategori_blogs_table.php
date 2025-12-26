<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('kategori_blogs', function (Blueprint $table) {
            $table->softDeletes(); // ini tambah kolom `deleted_at`
        });
    }

    public function down()
    {
        Schema::table('kategori_blogs', function (Blueprint $table) {
            $table->dropSoftDeletes(); // hapus kolom `deleted_at` kalau rollback
        });
    }
};
