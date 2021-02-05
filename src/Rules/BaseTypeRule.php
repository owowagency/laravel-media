<?php

namespace Owowagency\LaravelMedia\Rules;

use Owowagency\LaravelMedia\Rules\Concerns\ValidatesBase64;

abstract class BaseTypeRule extends IsBase64
{
    use ValidatesBase64;

    /**
     * The type to validate.
     *
     * @var string
     */
    protected $type;

    /**
     * StartsWithMimeType constructor.
     *
     * @param  string  $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
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

        return $this->validateType($this->getMimeType($value));
    }

    /**
     * Checks if base64 has valid type.
     *
     * @param  string  $mimeType
     * @return bool
     */
    abstract protected function validateType(string $mimeType): bool;

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('validation.custom.is_base_64_type', ['type' => $this->type]);
    }
}
