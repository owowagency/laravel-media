<?php

namespace Owowagency\LaravelMedia\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaResource extends JsonResource
{
    /**
     * Force the resource to use its own array structure instead of possible
     * exceptions.
     *
     * @var bool
     */
    public $forceMediaResource = false;

    /**
     * Create a new resource instance.
     */
    public function __construct(Media $resource, bool $forceMediaResource = false)
    {
        parent::__construct($resource);

        $this->forceMediaResource = $forceMediaResource;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        if (! $this->forceMediaResource && $this->isImage()) {
            return (new MediaImageResource($this->resource))->toArray($request);
        }

        return [
            'id' => $this->resource->id,
            'file_name' => $this->resource->file_name,
            'size' => $this->resource->size,
            'mime_type' => $this->resource->mime_type,
            'url' => $this->getUrl(),
        ];
    }

    /**
     * Determines if the resource is an image.
     */
    public function isImage(): bool
    {
        return Str::contains($this->resource->mime_type, 'image');
    }

    /**
     * Returns the url. The method of retrieving it varies on the disk it is
     * stored on.
     */
    public function getUrl(string $conversion = ''): string
    {
        $createTempUrl = config('laravel-media.temporary_urls', false);

        if (filter_var($createTempUrl, FILTER_VALIDATE_BOOLEAN)) {
            $expiration = now()->addMinutes(
                config('laravel-media.sign_expiration', 60),
            );

            return $this->resource->getTemporaryUrl($expiration, $conversion);
        }

        return $this->resource->getFullUrl($conversion);
    }

    /**
     * Create new anonymous resource collection.
     *
     * @return \Owowagency\LaravelMedia\Resources\MediaResourceCollection
     */
    public static function collection($resource, bool $forceMediaResource = false): MediaResourceCollection
    {
        return new MediaResourceCollection($resource, $forceMediaResource);
    }
}
