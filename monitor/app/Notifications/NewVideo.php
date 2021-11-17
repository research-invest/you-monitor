<?php

namespace App\Notifications;

use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Notifications\Notification;

class NewVideo extends Notification
{
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        $content = sprintf("У канала \n%s вышло видео \n%s",
            $notifiable->channel->title,
            $notifiable->title
        );

        return TelegramMessage::create()
            ->to('@youtube_monitor') // to env
            ->content($content)
            ->button('Video', $notifiable->url)
            ->button('Channel', $notifiable->channel->url);
    }
}
