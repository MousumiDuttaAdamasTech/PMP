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
        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('project_members_id'); 
            $table->unsignedBigInteger('project_role_id');
            $table->decimal('engagement_percentage', 5, 2)->default(00.00); 
            $table->date('start_date')->nullable();
            $table->decimal('duration', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('engagement_mode', ['daily', 'weekly', 'monthly','yearly'])->nullable();   
            $table->foreign('project_id')->references('id')->on('project');
            $table->foreign('project_members_id')->references('id')->on('users');           
            $table->foreign('project_role_id')->references('id')->on('project_role');      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_members');
    }
};
