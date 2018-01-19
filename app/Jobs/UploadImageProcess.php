<?php

namespace App\Jobs;

use App\Models\Convoy;
use Cache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Storage;
use Yangqi\Htmldom\Htmldom;

/**
 * Class UploadImageProcess
 *
 * @package App\Jobs
 */
class UploadImageProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public $tries = 5;
    /**
     * @var Convoy
     */
    private $convoy;

    /**
     * Create a new job instance.
     *
     * @param Convoy $convoy
     */
    public function __construct(Convoy $convoy)
    {

        $this->convoy = $convoy;
    }

    /**
     * Execute the job.
     * RESTART PHP WORKER AFTER ANY CHANGES!!!
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        if (!isset($this->convoy->background_url_safe) and isset($this->convoy->background_url)) {
            $this->convoy->background_url_safe = $this->link_download($this->convoy->background_url, 'background');
        }

        if (!isset($this->convoy->map_url_safe) and isset($this->convoy->map_url)) {
            $this->convoy->map_url_safe = $this->link_download($this->convoy->map_url, 'map');
        }

        if ($this->convoy->user->can('privileged', $this->convoy)) {
            $this->convoy->description = $this->img_download($this->convoy->description);
        }

        Cache::forget('convoy_' . $this->convoy->id);
        Convoy::flushEventListeners();
        $this->convoy->update();
    }

    /**
     * @param string $url
     *
     * @return mixed
     * @throws \Exception
     */
    protected function link_download(string $url)
    {
        try {
            $image = Image::make($url)->encode('jpg', 70);
        } catch (NotReadableException $exception) {
            return $url;
        }

        $date = date('YW', time());
        $name = md5($url);
        $path = "public/convoys/{$date}/{$name}.jpg";
        Storage::put($path, $image);

        return Storage::url($path);
    }

    /**
     * @param $html
     *
     * @return string
     */
    protected function img_download($html)
    {
        $desc = new Htmldom($html);
        foreach ($desc->find('img') as $image) {
            if (!$image->src) {
                continue;
            };

            if (str_contains($image->src, '/storage/convoys/')) {
                continue;
            }

            try {
                $src          = $image->src;
                $href         = Image::make($src);
                $cached_image = $href->encode($href->mime(), 70);

                $date = date('YW', time());

                $replacement = [
                    'image/gif'   => 'gif',
                    'image/png'   => 'png',
                    'image/x-png' => 'png',
                    'image/jpg'   => 'jpg',
                    'image/jpeg'  => 'jpg',
                    'image/pjpeg' => 'jpg',
                ];

                $ext = $replacement[$href->mime()];

                $name = md5($src) . '.' . $ext;
                $path = "public/convoys/{$date}/{$name}";
                Storage::put($path, $cached_image);

                $image->alt = $src;
                $image->src = Storage::url($path);
            } catch (NotReadableException $exception) {
                $image->outertext = '';
            }
        }

        return $desc;
    }
}
