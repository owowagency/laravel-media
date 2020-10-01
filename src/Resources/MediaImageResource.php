<?php

namespace Owowagency\LaravelBasicMedia\Resources;

class MediaImageResource extends MediaResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'original' => $this->getUrl(),
        ];

        $this->addConversions($data);

        return $data;
    }

    /**
     * Adds the conversions to the data that is being returned as array.
     *
     * @param  array  &$data
     * @return void
     */
    public function addConversions(array &$data): void
    {
        foreach ($this->resource->getMediaConversionNames() as $name) {
            $data[$name] = $this->getUrl($name);
        }
    }
}
