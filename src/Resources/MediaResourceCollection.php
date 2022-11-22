<?php

namespace Owowagency\LaravelMedia\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaResourceCollection extends ResourceCollection
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
    public function __construct($resource, bool $forceMediaResource = false)
    {
        parent::__construct($resource);

        $this->forceMediaResource = $forceMediaResource;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return $this->resource->map(function (MediaResource $resource) use ($request) {
            $resource->forceMediaResource = $this->forceMediaResource;

            return $resource;
        })->toArray();
    }
}
