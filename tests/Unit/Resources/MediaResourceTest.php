<?php

namespace Owowagency\LaravelBasicMedia\Tests\Unit\Resources;

use Mockery;
use Owowagency\LaravelBasicMedia\Tests\TestCase;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Owowagency\LaravelBasicMedia\Resources\MediaResource;
use Owowagency\LaravelBasicMedia\Tests\Support\App\Models\TestModel;

class MediaResourceTest extends TestCase
{
    /** @test */
    public function to_array()
    {
        // Make sure the media is not an image.
        [$media] = $this->prepare();

        $resource = MediaResource::make($media)->toArray(null);

        $this->assertMatchesJsonSnapshot($resource);
    }

    /** @test */
    public function to_array_image()
    {
        // Make sure the media is an image.
        [$media] = $this->prepare('image');

        $resource = MediaResource::make($media)->toArray(null);

        $this->assertMatchesJsonSnapshot($resource);
    }

    /** @test */
    public function to_array_image_force()
    {
        // Make sure the media is an image.
        [$media] = $this->prepare('image');

        $resource = MediaResource::make($media, true)->toArray(null);

        $this->assertMatchesJsonSnapshot($resource);
    }

    /** @test */
    public function is_image()
    {
        // Make sure the media is an image.
        [$media] = $this->prepare('image');

        $resource = MediaResource::make($media);

        $this->assertTrue($resource->isImage());
    }

    /** @test */
    public function is_not_image()
    {
        // Make sure the media is not an image.
        [$media] = $this->prepare();

        $resource = MediaResource::make($media);

        $this->assertFalse($resource->isImage());
    }

    /** @test */
    public function get_url()
    {
        [$media] = $this->prepare();

        // Create mock ...
        $mock = Mockery::mock($media);

        // ... and prepare expectations.
        $mock->shouldReceive('getFullUrl')->once();

        $mock->shouldNotReceive('getTemporaryUrl');

        // Make resource with mock.
        $resource = MediaResource::make($mock);

        $resource->getUrl();
    }

    /** @test */
    public function get_url_s3()
    {
        // Make sure disk is set to s3
        [$media] = $this->prepare('file', 's3');

        // Create mock ...
        $mock = Mockery::mock($media);

        // ... and prepare expectations.
        $mock->shouldReceive('getTemporaryUrl')->once();

        $mock->shouldNotReceive('getFullUrl');

        // Make resource with mock.
        $resource = MediaResource::make($mock);

        $resource->getUrl();
    }

    /**
     * Prepares for tests.
     *
     * @param  string  $mime
     * @param  string  $disk
     * @return array
     */
    private function prepare(string $mime = 'file', string $disk = 'public'): array
    {
        $model = TestModel::create([
            'value' => 'value',
        ]);

        $media = Media::create([
            'model_id' => $model->id,
            'model_type' => $model->getMorphClass(),
            'uuid' => '9891579b-ada1-430f-947f-69d4404a215d',
            'collection_name' => 'default',
            'name' => 'some_name',
            'file_name' => 'some_file_name',
            'mime_type' => $mime,
            'disk' => $disk,
            'conversions_disk' => $disk,
            'size' => 9999,
            'manipulations' => [],
            'custom_properties' => [],
            'responsive_images' => [],
            'order_column' => null,
        ]);

        return [$media];
    }
}
