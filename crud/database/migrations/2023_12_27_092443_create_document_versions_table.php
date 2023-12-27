<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentVersionsTable extends Migration
{
    public function up()
    {
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->string('doc_name');
            $table->unsignedBigInteger('doc_type_id');
            $table->text('comments')->nullable();
            $table->unsignedBigInteger('approved_by');
            $table->date('approved_on');
            $table->unsignedBigInteger('project_id');
            $table->string('version');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_versions');
    }
}
