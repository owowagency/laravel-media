<?php

namespace Owowagency\LaravelBasicMedia\Tests\Support\App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class TestModel extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
    ];
}
