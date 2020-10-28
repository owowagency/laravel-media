<?php

namespace Owowagency\LaravelMedia\Rules;

use Owowagency\LaravelMedia\Rules\Concerns\ValidatesBase64;

class IsBase64Image extends IsBase64
{
    use ValidatesBase64;

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

        return $this->isImageType($value);
    }

    /**
     * Checks if base64 is image.
     *
     * @param  string  $value
     * @return bool
     */
    protected function isImageType(string $value): bool
    {
        $mimeType = $this->getMimeType($value);

        $exploded = explode('/', $mimeType);

        return ($exploded[0] == 'image');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('validation.custom.is_base_64_image');
    }
}
