<?php

namespace Owowagency\LaravelMedia\Resources\Concerns;

trait HasMedia
{
    /**
     * Adds the image when loaded.
     *
     * @param  array  &$data
     * @return void
     */
    private function addImage(array &$data): void
    {
        if (! $this->resource->relationLoaded('media')) {
            return;
        }

        $data[$this->getImageAttributeName()] = resource($this->resource->media->first());
    }

    /**
     * Get the attribute name for the image.
     *
     * @return string
     */
    private function getImageAttributeName(): string
    {
        if (property_exists($this, 'imageAttributeName')) {
            return $this->imageAttributeName;
        }

        return 'image';
    }
}
