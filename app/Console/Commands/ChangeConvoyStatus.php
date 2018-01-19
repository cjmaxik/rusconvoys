<?php

namespace App\Console\Commands;

use App\Models\Convoy;
use Artisan;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class ChangeConvoyStatus
 *
 * @package App\Console\Commands
 */
class ChangeConvoyStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convoy:status {convoy? : ID of the convoy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and change convoy status';

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
     * @throws \Exception
     */
    public function handle()
    {
        $this->comment('now => ' . Carbon::now());
        $this->comment('');

        $convoy_id = $this->argument('convoy');
        if ($convoy_id) {
            $convoys = Convoy::findOrFail($convoy_id);
            $this->comment('Checking convoy ' . $convoy_id);
        } else {
            /** @var \Illuminate\Support\Collection|\App\Models\Convoy[] $convoys */
            $convoys = Convoy::whereNotIn('status', ['closed', 'draft', 'deleted'])->get();
            $this->comment('draft => ' . Convoy::where('status', 'draft')->count());
            $this->comment('open => ' . Convoy::where('status', 'open')->count());
            $this->comment('meeting => ' . Convoy::where('status', 'meeting')->count());
            $this->comment('on_way => ' . Convoy::where('status', 'on_way')->count());
            $this->comment('voting => ' . Convoy::where('status', 'voting')->count());
            $this->comment('closed => ' . Convoy::where('status', 'closed')->count());
            $this->comment('cancelled => ' . Convoy::where('status', 'cancelled')->count());
        }

        $this->comment('---------------------------------------------------------');

        if ($convoy_id) {
            $this->checkDate($convoys);
        } else {
            foreach ($convoys as $convoy) {
                $this->checkDate($convoy);
            }
        }

        Artisan::call('convoy:mail');
    }

    /**
     * @param $convoy
     */
    protected function checkDate($convoy)
    {
        $now              = Carbon::now();
        $meeting_datetime = Carbon::parse($convoy->meeting_datetime);
        $leaving_datetime = Carbon::parse($convoy->leaving_datetime);

        $last_status = $convoy->status;

        if (!in_array($last_status, ['draft', 'closed', 'cancelled'])) {

            if ($now->gte($meeting_datetime)) {
                $convoy->status = 'meeting';

                if ($now->gte($leaving_datetime)) {
                    $convoy->status = 'on_way';

                    if ($leaving_datetime->diffInMinutes($now, false) > 1 * 60) {
                        $convoy->status = 'voting';

                        if ($leaving_datetime->diffInMinutes($now, false)) {
                            $convoy->status = 'closed';
                        }
                    }
                }
            } else {
                $convoy->status = 'open';
            }
        } else {
            if (($last_status === 'cancelled') and ($convoy->updated_at->diffInMinutes($now, false) > 2 * 60)) {
                $convoy->delete();
            }
        }

        if ($last_status != $convoy->status) {
            $convoy->update();
            $this->comment($convoy->id . ' - ' . $last_status . ' => ' . $convoy->status);
        }
    }
}
