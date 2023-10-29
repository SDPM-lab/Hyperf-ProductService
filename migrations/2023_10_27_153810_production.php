<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class Production extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('production', function (Blueprint $table) {
            $table->increments('p_key');
            $table->string('name', 255)->nullable(false);
            $table->text('description')->nullable(false);
            $table->integer('price')->nullable(false);
            $table->timestamps();
            $table->timestamp('deleted_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('true');
    }
}
