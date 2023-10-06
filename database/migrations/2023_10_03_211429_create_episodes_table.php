<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('season_id');
            $table->string('show_id');
            $table->integer('episode_order_number');
            $table->string('tvdb_id');
            $table->boolean('downloaded');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
