<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('body');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('cause_id');
            $table->timestamps();

            $table->foreign('author_id')->on('members')->references('user_id');
            $table->foreign('post_id')->on('posts')->references('id');
            $table->foreign('cause_id')->on('causes')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
