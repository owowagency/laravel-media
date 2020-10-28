<?php

namespace Owowagency\LaravelMedia\Rules\Concerns;

trait GetsMimeTypeFromBase64
{
    /**
     * Reads mime type of string by using file info buffer.
     *
     * @param  string  $value
     * @return string
     */
    protected static function getMimeType(string $value): string
    {
        $fileData = base64_decode(static::removeScheme($value));

        $f = finfo_open();

        $mimeType = finfo_buffer($f, $fileData, FILEINFO_MIME_TYPE);

        finfo_close($f);

        return $mimeType;
    }

    /**
     * Removes data scheme from base64.
     *
     * @param  string  $value
     * @return string
     */
    protected static function removeScheme(string $value): string
    {
        if (strpos($value, ';base64') !== false) {
            list(, $value) = explode(';', $value);

            list(, $value) = explode(',', $value);
        }

        return $value;
    }

    /**
     * Returns the size of a base64 string in kilobytes.
     *
     * @param  string  $value
     * @return float
     */
    protected function getSize(string $value): float
    {
        return ((float) strlen(base64_decode($value))) / 1024;
    }
}
