<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qa_id');
            $table->unsignedBigInteger('tester_id');
            $table->unsignedBigInteger('task_id')->nullable();
            $table->string('bid');
            $table->unsignedBigInteger('bugType');
            $table->string('bugDescription');
            $table->string('bugStatus');
            $table->integer('priority');
            $table->string('severity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bugs');
    }
};
