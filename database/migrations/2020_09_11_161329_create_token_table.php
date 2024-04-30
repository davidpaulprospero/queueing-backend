<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('token', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('token_no', 10)->nullable();
            $table->string('studentId', 20)->nullable();
            $table->string('client_mobile', 20)->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('transaction_type_id')->unsigned()->nullable(); // Add this line
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')->onDelete('cascade')->name('transaction_types_name_foreign');
            $table->integer('counter_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('note', 512)->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->boolean('is_vip')->nullable();
            $table->boolean('status')->default(0)->comment('0-pending, 1-complete, 2-stop');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('token');
    }
}
