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
    protected $size;

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
        // Before we can validate the size we need to make sure that all values
        // are a base64 string.
        if (! parent::passes($attribute, $value)) {
            return false;
        }

        $values = Arr::wrap($value);

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
        return trans('validation.max.file', [
            'max' => $this->size,
        ]);
    }
}
