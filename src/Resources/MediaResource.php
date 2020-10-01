<?php

namespace Owowagency\LaravelBasicMedia\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;
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
     *
     * @param  \Spatie\MediaLibrary\MediaCollections\Models\Media  $resource
     * @param  bool  $forceMediaResource
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
     * @return array
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
            'url' => $this->getUrl(),
        ];
    }

    /**
     * Determines if the resource is an image.
     *
     * @return bool
     */
    public function isImage(): bool
    {
        return Str::contains($this->resource->mime_type, 'image');
    }

    /**
     * Returns the url. The method of retrieving it varies on the disk it is
     * stored on.
     *
     * @param  string  $conversion
     * @return string
     */
    public function getUrl(string $conversion = ''): string
    {
        switch ($this->resource->disk) {
            case 's3':
                $expiration = now()->addMinutes(
                    config('filesystems.disks.s3.sign_expiration', 3600)
                );

                return $this->resource->getTemporaryUrl($expiration, $conversion);
                break;

            default:
                return $this->resource->getFullUrl($conversion);
                break;
        }
    }

    /**
     * Create new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @param  bool  $forceMediaResource
     * @return \Owowagency\LaravelBasicMedia\Resources\MediaResourceCollection
     */
    public static function collection($resource, bool $forceMediaResource = false): MediaResourceCollection
    {
        return new MediaResourceCollection($resource, $forceMediaResource);
    }
}
