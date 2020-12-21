<?php

namespace Owowagency\LaravelMedia\Rules;

class IsBase64Subtype extends BaseTypeRule
{
    /**
     * Checks if base64 is image.
     *
     * @param  string  $mimeType
     * @return bool
     */
    protected function validateType(string $mimeType): bool
    {
        $exploded = explode('/', $mimeType);

        return $exploded[1] == $this->type;
    }
}
