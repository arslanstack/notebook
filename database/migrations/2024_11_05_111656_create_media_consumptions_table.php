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
        Schema::create('media_consumptions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('achievement');
            $table->timestamp('date');
            $table->tinyInteger('category_type')->default(0)->comment('0: Media Category, 1: Career Achievement');
            $table->foreignId('category_id')->constrained('media_consumption_categories');
            $table->string('category_text')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->integer('times_completed')->default(0);
            $table->tinyInteger('status')->default(0)->comment('0: Incomplete, 1: Complete, 3: On Hold, 4: Deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_consumptions');
    }
};
