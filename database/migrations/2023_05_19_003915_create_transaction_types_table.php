<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedbigInteger('department_id');
            $table->string('name');
            $table->string('key', 1)->nullable();
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('department_id')->references('id')->on('department')->onDelete('cascade')->name('transaction_types_department_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_types');
    }
}