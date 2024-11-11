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
        Schema::create('witness', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('complain_id')->references('id')->on('complains')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('witness');
    }
};
