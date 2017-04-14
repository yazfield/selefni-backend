<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToItemsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->timestamp('returned_at')->nullable()->default(null);
            $table->timestamp('borrowed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('returned_at');
            $table->dropColumn('borrowed_at');
        });
    }
}
