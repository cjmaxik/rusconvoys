<?php

namespace App\Console\Commands;

use App\Models\Convoy;
use App\Notifications\ConvoyIsWaiting;
use Illuminate\Console\Command;
use Jenssegers\Date\Date;
use Log;

class MailService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convoy:mail {--debug : Do not set \'mailed\' to True}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send convoy emails';

    /**
     * Create a new command instance.
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
        /** @var \Illuminate\Support\Collection|\App\Models\Convoy[] $convoys */
        $convoys = Convoy::whereIn('status', ['open', 'meeting'])->where('mailed', 0)->get();
        $now     = Date::now();

        $bar = $this->output->createProgressBar($convoys->count());

        foreach ($convoys as $convoy) {
            if ($convoy->participations->count()) {
                $meeting_datetime = Date::parse($convoy->meeting_datetime);
                if ($now->diffInMinutes($meeting_datetime, true) <= 30) {

                    foreach ($convoy->participations as $player) {
                        $player->user->notify(new ConvoyIsWaiting($player->user, $convoy));
                    }

                    if (!$this->option('debug')) {
                        $convoy->mailed = true;
                    }
                    $convoy->update();

                    if ($this->option('debug')) {
                        break;
                    }
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info('Done.');
    }
}
