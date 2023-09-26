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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table
                ->foreign('parent_id')
                ->references('id')
                ->on('courses')
                ->onDelete('set null')
                ->onUpdate('set null');
            $table->unsignedBigInteger('collection_id')->nullable();
            $table
                ->foreign('collection_id')
                ->references('id')
                ->on('collections')
                ->onDelete('set null')
                ->onUpdate('set null');
            $table->string('name');
            $table->string('code');
            $table->integer('year');
            $table->integer('credits');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
