<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 30)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->string('phase', 30)->nullable();
            $table->string('block', 30)->nullable();
            $table->string('lot', 30)->nullable();
            $table->string('barangay', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('building_number', 30)->nullable();
            $table->string('house_number', 30)->nullable();
            $table->string('unit_number', 30)->nullable();
            $table->string('street', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('municipality', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');   
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
        Schema::dropIfExists('contacts');
    }
}
