<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->string('title')->nullable();
            $table->string('firstname');
            $table->string('middle_initial')->nullable();
            $table->string('lastname');
            $table->string('suffix')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('address')->nullable();
            $table->string('specroyal');
            $table->unsignedBigInteger('user_id')
                    ->nullable();
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
                    $table->unsignedBigInteger('aro_user_id')
                    ->nullable();
            $table->foreign('aro_user_id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
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
        Schema::dropIfExists('authors');
    }
}
