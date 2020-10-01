<?php

namespace Owowagency\LaravelBasicMedia\Exceptions;

use Exception;

class UploadException extends Exception
{
    public function __construct($type = 'string')
    {
        $message = trans('laravel-basic-media::general.exception', [
            'type' => $type,
        ]);

        parent::__construct($message, 0, null);
    }
}
