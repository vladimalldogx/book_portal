<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->nullable();
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->unsignedBigInteger('author_assign_user_id')->nullable();
            $table->foreign('author_assign_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->unsignedBigInteger('author_aro_assign_user_id')->nullable();
            $table->foreign('author_aro_assign_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->string('isbn')->nullable();
            $table->string('title');
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
        Schema::dropIfExists('books');
    }
}
