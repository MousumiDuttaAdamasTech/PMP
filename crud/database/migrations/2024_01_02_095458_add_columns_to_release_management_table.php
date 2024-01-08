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
        Schema::table('release_management', function (Blueprint $table) {
            
            $table->string('rmid');

            $table->unsignedBigInteger('approved_by');
            $table->foreign('approved_by')->references('id')->on('project_members');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('release_management', function (Blueprint $table) {
            
            $table->dropColumn('rmid');
            $table->dropForeign(['approved_by']);
            $table->dropColumn('approved_by');
        });
    }
};
