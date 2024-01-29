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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('user_working_id')->nullable();
            $table->foreignId('project_id');
            $table->char('title',100);
            $table->text('description');
            $table->boolean('isDone')->default(false);
            $table->boolean('isWorking')->default(false);
            $table->int('work_time')->default('0');
            $table->timestamp('start_work_time')->nullable();
            $table->timestamps();
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
