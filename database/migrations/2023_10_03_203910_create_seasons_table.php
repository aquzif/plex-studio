<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->integer('show_id');
            $table->string('name');
            $table->integer('tvdb_id');
            $table->integer('season_order_number');
            $table->string('thumb_path');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
