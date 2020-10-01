<?php

namespace Owowagency\LaravelMedia\Exceptions;

use Exception;

class UploadException extends Exception
{
    /**
     * UploadException constructor.
     *
     * @param  string  $type
     */
    public function __construct(string $type = 'string')
    {
        $message = trans('laravel-basic-media::general.exception', [
            'type' => $type,
        ]);

        parent::__construct($message, 0, null);
    }
}
