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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

                $table->foreignId('company_id')->constrained()->onDelete('cascade');
                $table->foreignId('branch_id')->constrained()->onDelete('cascade');
                $table->string('first_name');
                $table->string('last_name');
                $table->string('employee_number')->unique()->nullable();
                $table->text('email')->nullable();
                $table->text('phone_number')->nullable();

            $table->timestamps();

            $table->index(['branch_id', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
