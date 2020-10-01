<?php

namespace Owowagency\LaravelMedia\Rules;

use Illuminate\Contracts\Validation\Rule;
use Owowagency\LaravelMedia\Rules\Concerns\GetsMimeTypeFromBase64;

class IsBase64 implements Rule
{
    use GetsMimeTypeFromBase64;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->isBase64($value);
    }

    /**
     * Determine if the given value is base64 encoded.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function isBase64($value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        // Remove data uri scheme
        $value = $this->removeScheme($value);

        // Check if can be decoded and encoded again.
        if (base64_decode($value, true) === false
            || base64_encode(base64_decode($value)) !== $value
        ) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('validation.custom.is_base_64');
    }
}
