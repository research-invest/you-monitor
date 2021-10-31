<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelSeeder extends Seeder
{
    public function run()
    {
        DB::table('channels')->insert([
            'url' => 'https://www.youtube.com/c/Drawkidsart',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
