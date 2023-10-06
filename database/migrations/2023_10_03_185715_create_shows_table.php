<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type',['movie','series']);
            $table->string('tvdb_id');
            $table->string('thumb_path');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shows');
    }
};
