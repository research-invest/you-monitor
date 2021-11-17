<?php

namespace App\Console\Commands;

use App\Models\Video;
use App\Notifications\NewVideo;
use Illuminate\Database\Eloquent\Model;
use NotificationChannels\Telegram\TelegramMessage;

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
        $this->testTelegram();
    }

    private function testTelegram()
    {
        $video = Video::find(1);
        $video->notify(new NewVideo([
            'url' => "Welcome to the application " . $video->title . "!"
        ]));

    }

}
