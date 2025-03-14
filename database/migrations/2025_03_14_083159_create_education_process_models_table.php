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
        Schema::create('education_process_models', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('role');
            $table->foreign('role')->references('role')->on('role_models');
            $table->bigInteger('org_id')->unsigned();
            $table->foreign('org_id')->references('id')->on('educational_org_models');
            $table->bigInteger('pro_id')->unsigned()->nullable();
            $table->foreign('pro_id')->references('id')->on('program_models');
            $table->bigInteger('curs_id')->unsigned()->nullable();
            $table->foreign('curs_id')->references('id')->on('curs_models');
            $table->bigInteger('subject_id')->unsigned()->nullable();
            $table->foreign('subject_id')->references('id')->on('subject_models');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_process_models');
    }
};
