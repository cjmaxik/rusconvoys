<?php
/**
 * Created by PhpStorm.
 * Date: 29.03.2017
 * Time: 15:04
 */

namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Original implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->encode('jpg', 70);
    }
}