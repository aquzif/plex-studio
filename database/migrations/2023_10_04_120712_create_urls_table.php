<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('urls', function (Blueprint $table) {
            $table->id();

            $table->string('url');
            $table->string('movie_id');
            $table->string('episode_id');
            $table->boolean('downloaded');
            $table->boolean('invalid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
