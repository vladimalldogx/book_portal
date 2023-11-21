<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEbookTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebook_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->unsignedBigInteger('book_id');
            $table->string('isbn');
            $table->string('instanceid');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->unsignedBigInteger('author_assign_user_id')
            ->nullable();
              $table->foreign('author_assign_user_id')
            ->references('id')
            ->on('users')
            ->onUpdate('cascade')
            ->onDelete('set null');
            $table->unsignedBigInteger('author_aro_assign_user_id')->nullable();
            $table->foreign('author_aro_assign_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->year('year');
            $table->string('month');
            $table->string('class_of_trade');
            $table->string('line_item_no');
            $table->string('transactiondate');
            $table->string('agentid');
            $table->string('teritorysold')->nullable();
            $table->integer('quantity');
            $table->double('price');
            $table->double('proceeds');
            $table->double('royalty');
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
        Schema::dropIfExists('ebook_transactions');
    }
}
