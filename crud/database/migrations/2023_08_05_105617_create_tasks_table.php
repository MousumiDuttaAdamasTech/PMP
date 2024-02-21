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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->text('epic')->nullable();
            $table->text('story')->nullable();
            $table->string('priority');
            $table->string('task_type')->nullable();
            $table->string('estimated_time'); 
            $table->integer('actual_hours')->nullable();
            $table->text('details');
            $table->string('assigned_to');
            $table->unsignedBigInteger('project_task_status_id');
            $table->unsignedBigInteger('sprint_id')->nullable();
            $table->foreign('sprint_id')->references('id')->on('sprints');
            $table->unsignedBigInteger('parent_task')->nullable();
            $table->foreign('parent_task')->references('id')->on('tasks'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
