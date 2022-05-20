<?php

namespace Owowagency\LaravelMedia\Tests\Unit\Rules;

use Illuminate\Support\Facades\Lang;
use Owowagency\LaravelMedia\Rules\Base64Max;
use Owowagency\LaravelMedia\Tests\TestCase;

class Base64MaxTest extends TestCase
{
    /**
     * A valid base64 string.
     *
     * @var string
     */
    protected $base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk/g8AAQsBBD48D9kAAAAASUVORK5CYII=';

    /** @test */
    public function passes_base64max_exact(): void
    {
        $this->assertTrue($this->validate($this->base64, 0.06640625));
    }

    /** @test */
    public function passes_base64max_smaller(): void
    {
        $this->assertTrue($this->validate($this->base64, 0.06640626));
    }

    /** @test */
    public function passes_base64max_array(): void
    {
        $base64 = [$this->base64, $this->base64];

        $this->assertTrue($this->validate($base64, 0.06640626 * 2));
    }

    /** @test */
    public function fails_base64max_bigger(): void
    {
        $this->assertFalse($this->validate($this->base64, 0.06640624));
    }

    /** @test */
    public function fails_base64max_non_base64(): void
    {
        $this->assertFalse($this->validate('no_base64', 0.06640625));
    }

    /** @test */
    public function it_returns_correct_message_on_fail(): void
    {
        Lang::addLines([
            'validation.max.file_with_unit' => 'The :attribute must not be greater than :max :unit.',
        ], Lang::getLocale(), 'laravel-media');

        // Loop through every unit starting with byte to terabyte
        for ($i = 0; $i < 5; $i++) {
            $rule = new Base64Max(0.012 * pow(1000, $i));

            $this->assertMatchesSnapshot($rule->message());
        }
    }

    /**
     * Validates the rule.
     *
     * @param  mixed  $base64
     * @param  float  $size
     * @return bool
     */
    private function validate($base64, float $size): bool
    {
        return (new Base64Max($size))->passes('', $base64);
    }
}
