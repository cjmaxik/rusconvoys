<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Storage;

/**
 * Class UploadAvatarProcess
 *
 * @package App\Jobs
 */
class UploadAvatarProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\User
     */
    private $user;

    /**
     * @var array
     */
    private $changes;


    /**
     * UploadAvatarProcess constructor.
     *
     * @param \App\Models\User $user
     * @param array            $changes
     */
    public function __construct(User $user, array $changes)
    {

        $this->user = $user;
        $this->changes = $changes;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        foreach ($this->changes as $attribute) {
            $this->user->{$attribute} = $this->download($this->user->{$attribute}, $attribute) ?: $this->user->{$attribute};
        }

        User::flushEventListeners();
        $this->user->update();
    }

    /**
     * @param string $url
     *
     * @param string $field
     *
     * @return mixed
     * @throws \Exception
     */
    protected function download(string $url, string $field)
    {
        try {
            $image = Image::make($url)->encode('jpg', 70);
        } catch (NotReadableException $exception) {
            return null;
        }

        $number = substr($this->user->id, 0, 1);
        $name = md5($url);
        $path = "public/avatars/{$number}/{$name}.jpg";
        Storage::put($path, $image);

        return Storage::url($path);
    }
}
