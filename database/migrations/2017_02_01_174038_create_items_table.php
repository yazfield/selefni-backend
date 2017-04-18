<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('details')->nullable();
            $table->integer('amount')->nullable();
            $table->enum('type', ['book', 'money', 'object'])->default('object');
            $table->timestamp('return_at');
            $table->integer('borrowed_to');
            $table->integer('borrowed_from');
            $table->timestamp('returned_at')->nullable();
            $table->timestamp('borrowed_at')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
