<?php

namespace Owowagency\LaravelMedia\Tests\Unit\Rules;

use Owowagency\LaravelMedia\Tests\TestCase;
use Owowagency\LaravelMedia\Rules\IsBase64MimeType;

class IsBase64MimeTypeTest extends TestCase
{
    /**
     * The IsBase64MimeType rule instance.
     *
     * @var \Owowagency\LaravelMedia\Rules\IsBase64MimeType
     */
    private $rule;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setup();

        $this->rule = new IsBase64MimeType('image/png');
    }

    /** @test */
    public function passes_valid_mime_type()
    {
        $this->assertTrue($this->validate($this->base64));
    }

    /** @test */
    public function fails_invalid_mime_type()
    {
        $txt = 'SWsgaGViIGluIGVlbiBwbGFudGVuYmFrIGdla29zdHMK';

        $this->assertFalse($this->validate($txt));
    }

    /** @test */
    public function fails_no_base64()
    {
        $this->assertFalse($this->validate('no_base_64'));
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
