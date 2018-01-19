<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Slack;

class CleanNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean all the database notifications older than 1 week';


    /**
     * Create a new console command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = DB::table('notifications')->count();
        $this->info('>>> ' . $count . ' notifications.');

        $now = Carbon::now();

        $notifications = DB::table('notifications')->get();

        $note = $notifications->filter(function ($notification) use ($now) {
            $created_at = Carbon::parse($notification->created_at);
            if ($created_at->diffInDays($now, false) >= 7) {
                return true;
            };

            return false;
        })->each(function ($notification) {
            DB::table('notifications')->where('id', $notification->id)->delete();
        });

        $note_count = $note->count();
        if ($note_count) {
            $this->info(">>> {$note_count} notifications has been deleted, sending info in Slack...");
            Slack::send("Удалено {$note_count} устаревших уведомлений. БУ!");
        } else {
            $this->info(">>> There is no old notifications.");
        }
    }
}
