<?php

namespace Owowagency\LaravelMedia\Rules;

use Illuminate\Support\Arr;

class Base64Max extends IsBase64
{
    /**
     * The size in kilobytes.
     *
     * @var float
     */
    protected float $size;

    /**
     * The Base64Max constructor.
     *
     * @param  float  $size
     */
    public function __construct(float $size)
    {
        $this->size = $size;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $values = Arr::wrap($value);

        // Before we can validate the size we need to make sure that all values
        // are a base64 string.
        if (! parent::passes($attribute, $values)) {
            return false;
        }

        $size = array_reduce($values, function ($carry, $value) {
            return $carry + $this->getSize($value);
        });

        return $size <= $this->size;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        $base = 1000;

        // to get the byte 🍑
        $size = $this->size * $base;

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $pow = floor(log($size) / log($base));

        return trans('laravel-media::validation.max.file_with_unit', [
            'max' => $size / pow($base, $pow),
            'unit' => $units[$pow],
        ]);
    }
}
