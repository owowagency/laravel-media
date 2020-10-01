<?php

namespace Owowagency\LaravelBasicMedia\Rules;

use Illuminate\Support\Arr;
use Owowagency\LaravelBasicMedia\Rules\Concerns\GetsMimeTypeFromBase64;

class IsBase64Type extends IsBase64
{
    use GetsMimeTypeFromBase64;

    /**
     * The base64 types to check in the test.
     *
     * @var array
     */
    private array $types;

    /**
     * Create a notification instance.
     *
     * @param  string|array  $type
     * @return void
     */
    public function __construct($types)
    {
        $this->types = Arr::wrap($types);
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
        if (! $this->isBase64($value)) {
            return false;
        }

        return in_array($this->getMimeType($value), $this->types);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('validation.custom.is_base_64_type');
    }
}
