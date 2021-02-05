<?php

namespace Owowagency\LaravelMedia\Rules;

class IsBase64MimeType extends BaseTypeRule
{
    /**
     * Checks if base64 has a valid MIME type
     *
     * @param  string  $mimeType
     * @return bool
     */
    protected function validateType(string $mimeType): bool
    {
        return $mimeType == $this->type;
    }
}
