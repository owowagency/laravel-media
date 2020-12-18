<?php

namespace Owowagency\LaravelMedia\Rules;

use Owowagency\LaravelMedia\Rules\Concerns\ValidatesBase64;

class StartsWithMimeType extends IsBase64
{
    use ValidatesBase64;

    /**
     * The mime type to validate.
     *
     * @var string
     */
    protected $mimeType;

    /**
     * StartsWithMimeType constructor.
     *
     * @param  string  $mimeType
     */
    public function __construct(string $mimeType)
    {
        $this->mimeType = $mimeType;
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

        return $this->startsWithMimeType($value);
    }

    /**
     * Checks if base64 is image.
     *
     * @param  string  $value
     * @return bool
     */
    protected function startsWithMimeType(string $value): bool
    {
        $mimeType = $this->getMimeType($value);

        $exploded = explode('/', $mimeType);

        return ($exploded[0] == $this->mimeType);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('validation.custom.is_base_64_type', ['type' => $this->mimeType]);
    }
}
