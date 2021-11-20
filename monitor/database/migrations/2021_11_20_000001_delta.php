<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Delta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_data_videos', function (Blueprint $table) {
            $table->decimal('delta')->default(0)->nullable(false);
        });

        Schema::table('history_data_channels', function (Blueprint $table) {
            $table->decimal('delta')->default(0)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('history_data_videos', ['delta']);
        Schema::dropIfExists('history_data_channels', ['delta']);
    }
}
