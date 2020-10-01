<?php

namespace Owowagency\LaravelBasicMedia\Rules\Concerns;

trait GetsMimeTypeFromBase64
{
    /**
     * Reads mime type of string by using file info buffer.
     *
     * @param  string  $value
     * @return string
     */
    protected function getMimeType(string $value): string
    {
        $fileData = base64_decode($this->removeScheme($value));

        $f = finfo_open();

        $mimeType = finfo_buffer($f, $fileData, FILEINFO_MIME_TYPE);

        finfo_close($f);

        return $mimeType;
    }
}
