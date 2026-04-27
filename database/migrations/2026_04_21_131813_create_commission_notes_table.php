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
        Schema::create('commission_notes', function (Blueprint $table) {
            $table->id();

                $table->foreignId('company_id')->constrained()->onDelete('cascade');
                $table->foreignId('branch_id')->constrained()->onDelete('cascade');
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
                $table->decimal('amount', 12, 2);
                $table->text('note')->nullable();
                
            $table->timestamps();

            $table->index(['company_id', 'branch_id']);
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_notes');
    }
};
