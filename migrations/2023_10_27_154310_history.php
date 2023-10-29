<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class History extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('history', function (Blueprint $table) {
            $table->increments('h_key');
            $table->integer('p_key')->nullable(false)->unsigned();
            $table->string('o_key', 255)->nullable(false);
            $table->integer('amount')->nullable(false);
            $table->string('type', 255)->nullable(false);
            $table->timestamps();
            $table->timestamp('deleted_at', 0)->nullable();
        });

        Schema::table('history', function($table) {
            $table->foreign('p_key')->references('p_key')->on('production');
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
