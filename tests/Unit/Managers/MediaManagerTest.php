<?php

namespace Owowagency\LaravelMedia\Tests\Unit\Managers;

use Mockery;
use Owowagency\LaravelMedia\Managers\MediaManager;
use Owowagency\LaravelMedia\Tests\Support\Models\TestModel;
use Owowagency\LaravelMedia\Tests\TestCase;
use Spatie\MediaLibrary\MediaCollections\FileAdderFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaManagerTest extends TestCase
{
    private $url = 'https://some-url.come';

    protected MediaManager $mediaManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mediaManager = new MediaManager();
    }

    /** @test */
    public function upload_array()
    {
        [$model, $fileAdder, $media] = $this->prepare();

        // Two files need to be uploaded: one base64 and one url.
        $fileAdder->shouldReceive('toMediaCollection')
            ->twice()
            ->andReturn($media);

        $model->shouldReceive('addMediaFromBase64')
            ->with($this->base64)
            ->andReturn($fileAdder);

        $model->shouldReceive('addMediaFromUrl')
            ->with($this->url)
            ->andReturn($fileAdder);

        $result = $this->mediaManager->upload($model, [$this->base64, $this->url]);

        $this->assertEquals([$media, $media], $result);
    }

    /** @test */
    public function upload_assoc_array()
    {
        [$model, $fileAdder, $media] = $this->prepare();

        // One base 64 file needs to be uploaded.
        $fileAdder->shouldReceive('toMediaCollection')
            ->once()
            ->andReturn($media);

        $model->shouldReceive('addMediaFromBase64')
            ->with($this->base64)
            ->andReturn($fileAdder);

        $result = $this->mediaManager->upload($model, [
            'file' => $this->base64,
        ]);

        $this->assertEquals([$media], $result);
    }

    /** @test */
    public function upload_string()
    {
        [$model, $fileAdder, $media] = $this->prepare();

        // One base 64 file needs to be uploaded.
        $fileAdder->shouldReceive('toMediaCollection')
            ->once()
            ->andReturn($media);

        $model->shouldReceive('addMediaFromBase64')
            ->with($this->base64)
            ->andReturn($fileAdder);

        $result = $this->mediaManager->upload($model, $this->base64);

        $this->assertEquals([$media], $result);
    }

    /** @test */
    public function upload_base64_file_name()
    {
        [$model, $fileAdder, $media] = $this->prepare();

        // One base 64 file needs to be uploaded with name.
        $name = 'some_name';

        $fileAdder->shouldReceive('usingName')
            ->once()
            ->with($name)
            ->andReturnSelf();

        $fileAdder->shouldReceive('usingFileName')
            ->once()
            ->with($name.'.gif')
            ->andReturnSelf();

        $fileAdder->shouldReceive('toMediaCollection')
            ->once()
            ->andReturn($media);

        $model->shouldReceive('addMediaFromBase64')
            ->with($this->base64)
            ->andReturn($fileAdder);

        $result = $this->mediaManager->uploadFromBase64($model, $this->base64, $name);

        $this->assertEquals($media, $result);
    }

    /**
     * Prepares for tests.
     */
    private function prepare(): array
    {
        $model = Mockery::mock(new TestModel);

        $fileAdder = Mockery::mock(app(FileAdderFactory::class)->create($model, ''));

        $media = new Media;

        return [$model, $fileAdder, $media];
    }
}
