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
        Schema::create('curs_models', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent')->unsigned();
            $table->foreign('parent')->references('id')->on('program_models');
            $table->bigInteger('created_admin')->unsigned();
            $table->foreign('created_admin')->references('id')->on('users');
            $table->string('number')->unique();
            $table->string('name');
            $table->string('description');
            $table->string('image');
            $table->date("start");
            $table->date("final");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curs_models');
    }
};
