<?php

namespace Owowagency\LaravelMedia\Rules;

use Owowagency\LaravelMedia\Rules\Concerns\ValidatesBase64;

class IsBase64Audio extends StartsWithMimeType
{
    use ValidatesBase64;

    /**
     * IsBase64Audio constructor.
     */
    public function __construct()
    {
        parent::__construct('audio');
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return trans('validation.custom.is_base_64_audio');
    }
}
