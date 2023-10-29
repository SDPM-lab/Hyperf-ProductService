<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class Inventory extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->integer('p_key')->nullable(false)->unsigned();
            $table->integer('amount')->nullable(false);
            $table->timestamps();
            $table->timestamp('deleted_at', 0)->nullable();
            
        });

        Schema::table('inventory', function($table) {
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
