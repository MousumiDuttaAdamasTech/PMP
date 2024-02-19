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
        Schema::create('sprints', function (Blueprint $table) {
            $table->id();
            $table->string('sprint_name');
            $table->float('estimated_hrs');
            $table->float('actual_hrs')->nullable();
            $table->string('sprint_status');
            $table->date('current_date')->nullable();
            $table->date('sprint_planningDate')->nullable(); // Add this line for sprint planning date
            $table->date('sprint_taskDiscuss')->nullable(); // Add this line for sprint task discussion date
            $table->date('sprint_startDate')->nullable(); // Add this line for sprint start date
            $table->date('sprint_endDate')->nullable(); // Add this line for sprint end date
            $table->date('sprint_demoDate')->nullable(); 
            $table->unsignedBigInteger('assign_to');
            //$table->unsignedBigInteger('task_status_id');
            $table->unsignedBigInteger('projects_id');
            $table->boolean('is_active');
            $table->timestamps();

            // Foreign key constraints
            //$table->foreign('assign_to')->references('id')->on('project_members');
            //$table->foreign('task_status_id')->references('id')->on('task_status');
            $table->foreign('projects_id')->references('id')->on('project');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sprints');
    }
};