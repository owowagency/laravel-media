<?php

namespace Owowagency\LaravelMedia\Rules;

use Illuminate\Support\Arr;
use Owowagency\LaravelMedia\Rules\Concerns\ValidatesBase64;

class IsBase64Type extends IsBase64
{
    use ValidatesBase64;

    /**
     * The base64 types to check in the test.
     *
     * @var array
     */
    private $types;

    /**
     * Create a notification instance.
     *
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
     */
    public function message(): string
    {
        return trans('validation.custom.is_base_64_type', ['type' => implode(',', $this->types)]);
    }
}
