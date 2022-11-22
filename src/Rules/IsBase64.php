<?php

namespace Owowagency\LaravelMedia\Rules;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Validation\Rule;
use Owowagency\LaravelMedia\Rules\Concerns\ValidatesBase64;

class IsBase64 implements Rule
{
    use ValidatesBase64;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     */
    public function passes($attribute, $value): bool
    {
        $values = Arr::wrap($value);

        foreach ($values as $value) {
            if (! $this->isBase64($value)) {
                return false;
            }
        }

        // All base64 strings are valid. Now we only need to verify if there
        // where any base64 strings at all.
        return count($values) > 0;
    }

    /**
     * Determine if the given value is base64 encoded.
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
     */
    public function message(): string
    {
        return trans('validation.custom.is_base_64');
    }
}
