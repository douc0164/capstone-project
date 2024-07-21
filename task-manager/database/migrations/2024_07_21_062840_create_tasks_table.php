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
        // this will be connected to the proper list and display all tasks
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name', 50); 
            $table->date('due_date')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->foreignId('priority_id')->nullable()->constrained('priorities')->onDelete('set null');
            $table->timestamps();
            $table->foreignId('list_id')->constrained('task_lists')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
