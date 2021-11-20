<?php

namespace App\Console\Commands;

use App\Helpers\MathHelper;
use App\Models\Video;
use App\Notifications\NewVideo;

/**
 * php artisan misc:run
 */
class Misc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'misc:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Разные тестовые команды';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $this->testTelegram();
        $this->testDelta();
    }

    private function testTelegram()
    {
        $video = Video::find(1);
        $video->notify(new NewVideo([
            'url' => "Welcome to the application " . $video->title . "!"
        ]));

    }

    private function testDelta()
    {
        $tests = [
            [
                'old' => 0,
                'new' => 0,
                'result' => 0
            ],
            [
                'old' => 100,
                'new' => 120,
                'result' => 20
            ],
            [
                'old' => 120,
                'new' => 100,
                'result' => -20
            ],
            [
                'old' => 1,
                'new' => 120,
                'result' => 11900
            ],
            [
                'old' => 120,
                'new' => 1,
                'result' => -11900
            ],
            [
                'old' => 100,
                'new' => 1200,
                'result' => 1100
            ], // 5
            [
                'old' => 100,
                'new' => 0,
                'result' => -100
            ],
            [
                'old' => 0,
                'new' => 100,
                'result' => 100
            ],
        ];

        foreach ($tests as $num => $test) {
            $percent = MathHelper::getPercentageChange($test['old'], $test['new']);

            if ($percent != $test['result']) {
                var_dump([
                    $num,
                    $percent,
                    $test['result']
                ]);
            }

        }
    }

}
