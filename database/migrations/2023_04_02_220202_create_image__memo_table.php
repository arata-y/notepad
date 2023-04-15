<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image__memo', function (Blueprint $table) {
            $table->foreignId('image_id')->constrained();
            $table->foreignId('memo_id')->constrained();
            $table->primary(['image_id','memo_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image__memo');
    }
};
