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
        Schema::table('task_users', function (Blueprint $table) {
            // Add assigned_to column
            $table->unsignedBigInteger('assigned_to');

            // Add foreign key constraint for assigned_to column
            $table->foreign('assigned_to')->references('id')->on('project_members');

            // Add allotted_to column
            $table->unsignedBigInteger('allotted_to');

            // Add foreign key constraint for allotted_to column
            $table->foreign('allotted_to')->references('id')->on('project_members');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_users', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['assigned_to']);
            $table->dropForeign(['allotted_to']);

            // Drop columns
            $table->dropColumn('assigned_to');
            $table->dropColumn('allotted_to');
        });
    }
};
