<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('follow_id');
            $table->timestamps();
            $table->primary(['user_id', 'follow_id']);
        });
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('comment')->nullable();
            $table->datetime('reserve')->nullable();
            $table->integer('visibility');
            // $table->bigInteger('reply_id')->nullable();
            $table->timestamps();
        });
        // Schema::create('tags', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('tag');
        //     $table->timestamps();
        // });
        // Schema::create('posts_tags', function (Blueprint $table) {
        //     $table->bigInteger('post_id');
        //     $table->bigInteger('tag_id');
        //     $table->timestamps();
        //     $table->primary(['post_id', 'tag_id']);
        // });
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->string('image');
            $table->timestamps();
        });
        Schema::create('favorites', function (Blueprint $table) {
            $table->bigInteger('post_id');
            $table->bigInteger('user_id');
            $table->timestamps();
            $table->primary(['user_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('follows');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('posts_tags');
        Schema::dropIfExists('images');
        Schema::dropIfExists('favorites');
    }
}
