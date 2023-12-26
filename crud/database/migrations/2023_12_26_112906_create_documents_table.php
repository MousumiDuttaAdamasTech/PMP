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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('doc_uuid')->unique();
            $table->unsignedBigInteger('doc_type_id');
            $table->foreign('doc_type_id')->references('id')->on('doctypes');
            $table->string('doc_name');
            $table->string('version');
            $table->text('comments')->nullable();
            $table->unsignedBigInteger('approved_by');
            $table->foreign('approved_by')->references('id')->on('project_members');
            $table->date('approved_on')->useCurrent(); // Automatically set to the current date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
