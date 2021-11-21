<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('medium_post_id')->nullable();
            $table->string('title');
            $table->string('content');
            $table->string('tags');
            $table->string('publishStatus');
            $table->string('url')->nullable();
            // $table->integer('image_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('posts', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        // Schema::table('images', function($table) {
        //     $table->foreign('image_id')->references('id')->on('images');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
