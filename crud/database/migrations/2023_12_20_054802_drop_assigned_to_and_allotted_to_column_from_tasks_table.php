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
        Schema::table('tasks', function (Blueprint $table) {
            // Drop assigned_to column
            $table->dropForeign(['assigned_to']);
            $table->dropColumn('assigned_to');
            //Drop allotted_to column
            $table->dropForeign(['allotted_to']);
            $table->dropColumn('allotted_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Add assigned_to column back (you may need to adjust the type)
            $table->unsignedBigInteger('assigned_to');
            // Add assigned_to column back (you may need to adjust the type)
            $table->unsignedBigInteger('allotted_to');
        });
    }
};
