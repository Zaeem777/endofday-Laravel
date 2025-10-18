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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bakery_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->integer('price');
            $table->integer('discountedprice')->nullable();
            $table->string('category');
            $table->integer('remainingitem');
            $table->string('image')->nullable();
            $table->date('manufacturedate');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing');
    }
};