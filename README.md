# you-monitor

cron
* * * * * cd /var/www/app && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1