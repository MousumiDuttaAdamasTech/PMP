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
        Schema::create('release_management', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('project');
            $table->string('name');
            $table->text('details');
            $table->date('release_date');
            $table->timestamps();
        });

        Schema::create('release_management_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('release_management_id');
            $table->foreign('release_management_id')->references('id')->on('release_management');
            $table->string('document_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('release_management_documents');
        Schema::dropIfExists('release_management');
    }
};
