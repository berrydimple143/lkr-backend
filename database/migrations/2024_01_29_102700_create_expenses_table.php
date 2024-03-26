<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('description', 100)->nullable();
            $table->string('person_in_charge', 100)->nullable();
            $table->string('percentage', 10)->nullable();
            $table->string('acknowledge_by', 100)->nullable();
            $table->string('type', 30)->nullable();
            $table->decimal('amount', 10, 2);
            $table->timestamp('transaction_date')->nullable();
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
        Schema::dropIfExists('expenses');
    }
}
