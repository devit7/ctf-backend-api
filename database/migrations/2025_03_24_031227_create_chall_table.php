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
        Schema::create('chall', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('flag');
            $table->integer('point');
            $table->string('author');
            $table->enum('status', ['open', 'closed']);
            $table->string('attachment')->nullable();
            $table->foreignId('category_id')->constrained('category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chall');
    }
};
