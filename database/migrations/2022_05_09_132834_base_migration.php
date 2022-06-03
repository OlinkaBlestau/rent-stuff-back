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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('phone');
            $table->string('password');
            $table->string('photo')->nullable();
            $table->string('role');
            $table->timestamps();

        });

        Schema::create('shops', function (Blueprint $table){
            $table->id()->autoIncrement();
            $table->string('name');
            $table->string('e-mail' );
            $table->string('phone');
            $table->string('address');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('description');
            $table->timestamps();
         });


        Schema::create('things', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->string('price' );
            $table->string('description');
            $table->string('photo')->nullable();
            $table->dateTime('created')->nullable()->useCurrent();

        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->dateTime('date');
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table){
            $table->id()->autoIncrement();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('shops', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });

        Schema::table('things', function (Blueprint $table) {
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('thing_id')->constrained('things')->onDelete('cascade');

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('users');
        Schema::dropIfExists('shops');
        Schema::dropIfExists('things');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('categories');
    }
};
