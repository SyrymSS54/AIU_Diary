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
        Schema::create('educational_org_models', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('created_admin')->unsigned();
            $table->foreign('created_admin')->references('id')->on('users');
            $table->string('name')->unique();
            $table->string('address');
            $table->string('number')->unique();
            $table->text('description');
            $table->string('image')->default('default.jpg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_org_models');
    }
};
