<?php

namespace Owowagency\LaravelMedia\Tests\Unit\Rules;

use Owowagency\LaravelMedia\Tests\TestCase;
use Owowagency\LaravelMedia\Rules\IsBase64Audio;

class IsBase64AudioTest extends TestCase
{
    /**
     * The IsBase64Audio rule instance.
     *
     * @var \Owowagency\LaravelMedia\Rules\IsBase64Audio
     */
    private $rule;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setup();

        $this->base64 = file_get_contents(__DIR__ . '/../../Support/content/audio');
        $this->rule = new IsBase64Audio();
    }

    /** @test */
    public function passes_audio()
    {
        $this->assertTrue($this->validate($this->base64));
    }

    /** @test */
    public function fails_no_base64()
    {
        $this->assertFalse($this->validate('no_base_64'));
    }

    /** @test */
    public function fails_no_audio()
    {
        $txt = 'SWsgaGViIGluIGVlbiBwbGFudGVuYmFrIGdla29zdHMK';

        $this->assertFalse($this->validate($txt));
    }

    /**
     * Validates the rule.
     *
     */
    private function validate($base64): bool
    {
        return $this->rule->passes('', $base64);
    }
}
