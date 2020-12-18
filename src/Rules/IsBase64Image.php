<?php

namespace Owowagency\LaravelMedia\Rules;

use Owowagency\LaravelMedia\Rules\Concerns\ValidatesBase64;

class IsBase64Image extends StartsWithMimeType
{
    use ValidatesBase64;

    /**
     * IsBase64Image constructor.
     */
    public function __construct()
    {
        parent::__construct('image');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('validation.custom.is_base_64_image');
    }
}
