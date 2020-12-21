<?php

namespace Owowagency\LaravelMedia\Rules;

use Owowagency\LaravelMedia\Rules\Concerns\ValidatesBase64;

class IsBase64MimeType extends BaseTypeRule
{
    /**
     * Checks if base64 is image.
     *
     * @param  string  $mimeType
     * @return bool
     */
    protected function validateType(string $mimeType): bool
    {
        return $mimeType == $this->type;
    }
}
