<?php

namespace Owowagency\LaravelMedia\Tests\Unit\Resources;

use Owowagency\LaravelMedia\Tests\TestCase;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Owowagency\LaravelMedia\Resources\MediaImageResource;
use Owowagency\LaravelMedia\Tests\Support\Models\TestModel;

class ImageResourceTest extends TestCase
{
    /** @test */
    public function to_array()
    {
        [$media] = $this->prepare();

        $resource = MediaImageResource::make($media)->toArray(null);

        $this->assertMatchesJsonSnapshot($resource);
    }

    /**
     * Prepares for tests.
     *
     * @return array
     */
    private function prepare(): array
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
            'mime_type' => 'image',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 9999,
            'manipulations' => [],
            'custom_properties' => [],
            'responsive_images' => [],
            'order_column' => null,
        ]);

        return [$media];
    }
}
