# you-monitor


cron


0 */6 * * * cd /home/koval/you-monitor/docker && make get-channel-info
0 * * * * cd /home/koval/you-monitor/docker && make get-new-video-channels-by-rss
*/30 * * * * cd /home/koval/you-monitor/docker && make get-video-info


