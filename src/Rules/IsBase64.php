<?php

namespace Owowagency\LaravelBasicMedia\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsBase64 implements Rule
{
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
     * Removes data scheme from base64.
     *
     * @param  string  $value
     * @return string
     */
    protected function removeScheme(string $value): string
    {
        if (strpos($value, ';base64') !== false) {
            list(, $value) = explode(';', $value);

            list(, $value) = explode(',', $value);
        }

        return $value;
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
