<?php

namespace Owowagency\LaravelMedia\Tests\Unit\Rules;

use Owowagency\LaravelMedia\Tests\TestCase;
use Owowagency\LaravelMedia\Rules\IsBase64Subtype;

class IsBase64SubtypeTest extends TestCase
{
    /**
     * The IsBase64Type rule instance.
     *
     * @var \Owowagency\LaravelMedia\Rules\IsBase64Type
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

        $this->rule = new IsBase64Subtype('png');
    }

    /** @test */
    public function passes_image()
    {
        $this->assertTrue($this->validate($this->base64));
    }

    /** @test */
    public function fails_no_base64()
    {
        $this->assertFalse($this->validate('no_base_64'));
    }

    /** @test */
    public function fails_no_image()
    {
        $txt = 'SWsgaGViIGluIGVlbiBwbGFudGVuYmFrIGdla29zdHMK';

        $this->assertFalse($this->validate($txt));
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
