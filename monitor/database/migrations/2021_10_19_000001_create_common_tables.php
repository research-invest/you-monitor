<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommonTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('url');
            $table->string('channel_id')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->integer('count_views')->nullable();
            $table->integer('count_subscribers')->nullable();
            $table->smallInteger('status')->default(\App\Models\Channel::STATUS_ACTIVE);
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('channel_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('url');
            $table->string('video_id');
            $table->dateTime('published_at');
            $table->string('thumbnail_url');
            $table->integer('views')->nullable();
            $table->integer('rating_count')->nullable();
            $table->integer('length_seconds')->nullable();
            $table->smallInteger('status')->default(\App\Models\Video::STATUS_ACTIVE);
            $table->timestamps();

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels')
                ->onDelete('cascade');

            $table->index('channel_id');
            $table->index('video_id');
        });

        Schema::create('video_previews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('channel_id');
            $table->bigInteger('video_id');
            $table->string('thumbnail_url');
            $table->string('hash');
            $table->timestamps();

            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->onDelete('cascade');

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels')
                ->onDelete('cascade');

            $table->index('channel_id');
            $table->index('video_id');
        });

        Schema::create('change_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('channel_id');
            $table->bigInteger('video_id');
            $table->string('log');

            $table->timestamps();

            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->onDelete('cascade');

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels')
                ->onDelete('cascade');

            $table->index('channel_id');
            $table->index('video_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('change_logs');
        Schema::dropIfExists('video_previews');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('channels');
    }
}
