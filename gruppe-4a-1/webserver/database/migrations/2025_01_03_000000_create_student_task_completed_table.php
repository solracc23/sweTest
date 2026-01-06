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
        // Nur erstellen wenn die Tabelle nicht existiert
        if (!Schema::hasTable('studentTaskCompleted')) {
            Schema::create('studentTaskCompleted', function (Blueprint $table) {
                $table->integer('studentTaskID')->primary();
                $table->unsignedBigInteger('userID')->nullable();
                $table->integer('taskID')->nullable();

                $table->foreign('taskID')
                    ->references('taskID')
                    ->on('task')
                    ->onDelete('cascade');

                $table->foreign('userID')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

                $table->index('taskID');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studentTaskCompleted');
    }
};
