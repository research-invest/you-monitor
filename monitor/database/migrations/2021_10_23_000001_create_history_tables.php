<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_data_videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('video_id');
            $table->bigInteger('channel_id');
            $table->integer('views');
            $table->integer('likes');
            $table->integer('dis_likes');
            $table->decimal('average_rating')->nullable();
            $table->timestamps();

            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->onDelete('cascade');

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels')
                ->onDelete('cascade');

            $table->index('video_id');
            $table->index('channel_id');
        });

        Schema::create('history_data_channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('channel_id');
            $table->integer('views');
            $table->timestamps();

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels')
                ->onDelete('cascade');

            $table->index('channel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_data_videos');
        Schema::dropIfExists('history_data_channels');
    }
}
