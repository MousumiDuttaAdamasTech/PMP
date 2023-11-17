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
        Schema::create('revenue_budgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('financial_year_id');
            $table->unsignedBigInteger('vertical_id');
            $table->enum('budget_type', ['weekly', 'monthly', 'quarterly', 'yearly']);
            $table->dateTime('period_start');
            $table->dateTime('period_end');
            $table->string('period_name');
            $table->decimal('value', 10, 2);
            $table->string('unit')->default('INR');
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->foreign('financial_year_id')->references('id')->on('financial_years');
            $table->foreign('vertical_id')->references('id')->on('vertical');
            $table->foreign('parent_id')->references('id')->on('revenue_budgets');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_budgets');
    }
};
