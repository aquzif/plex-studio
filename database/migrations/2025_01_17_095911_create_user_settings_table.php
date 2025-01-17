<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->string('sort_by');
            $table->string('sort_type');
            $table->boolean('show_only_incomplete');
            $table->boolean('show_only_favourites');
            $table->string('hide_downloaded_episodes');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
