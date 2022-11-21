<?php

namespace Owowagency\LaravelMedia\Tests\Unit\Rules;

use Owowagency\LaravelMedia\Tests\TestCase;
use Owowagency\LaravelMedia\Rules\IsBase64Type;

class IsBase64TypeTest extends TestCase
{
    /**
     * The IsBase64Type rule instance.
     *
     * @var \Owowagency\LaravelMedia\Rules\IsBase64Type
     */
    private $rule;

    /**
     * A valid base64 string.
     *
     * @var string
     */
    protected $base64 = 'Ym9keSB7CiAgICBiYWNrZ3JvdW5kOiAjMDAwOwp9Cg==';

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setup();

        $this->rule = new IsBase64Type('text');
    }

    /** @test */
    public function passes_images()
    {
        $this->rule = new IsBase64Type('image');

        $image = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk/g8AAQsBBD48D9kAAAAASUVORK5CYII=';

        $this->assertTrue($this->validate($image));
    }

    /** @test */
    public function passes_text_css_file()
    {
        $this->assertTrue($this->validate($this->base64));
    }

    /** @test */
    public function fails_no_base64()
    {
        $this->assertFalse($this->validate('no_base_64'));
    }

    /** @test */
    public function fails_no_text_css_file()
    {
        $image = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk/g8AAQsBBD48D9kAAAAASUVORK5CYII=';

        $this->assertFalse($this->validate($image));
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
