<?php

namespace Owowagency\LaravelMedia\Tests\Unit\Rules;

use Owowagency\LaravelMedia\Rules\IsBase64;
use Owowagency\LaravelMedia\Tests\TestCase;

class IsBase64Test extends TestCase
{
    /**
     * The IsBase64 rule instance.
     *
     * @var \Owowagency\LaravelMedia\Rules\IsBase64
     */
    private $rule;

    /**
     * The scheme that belongs to the base64.
     *
     * @var string
     */
    private $scheme = 'data:image/gif;base64,';

    /**
     * A valid base64 string.
     *
     * @var string
     */
    protected $base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk/g8AAQsBBD48D9kAAAAASUVORK5CYII=';

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setup();

        $this->rule = new IsBase64;
    }

    /** @test */
    public function passes_base64()
    {
        $this->assertTrue($this->validate($this->base64));
    }

    /** @test */
    public function passes_with_scheme()
    {
        $withScheme = $this->scheme . $this->base64;

        $this->assertTrue($this->validate($withScheme));
    }

    /** @test */
    public function fails_no_base_64()
    {
        $this->assertFalse($this->validate('no_base_64'));
    }

    /** @test */
    public function fails_no_string()
    {
        $this->assertFalse($this->validate([]));
    }

    /**
     * Validates the rule.
     *
     * @param  mixed  $base64
     * @return bool
     */
    private function validate($base64): bool
    {
        return $this->rule->passes('', $base64);
    }
}
