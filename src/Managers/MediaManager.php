<?php

namespace Owowagency\LaravelMedia\Managers;

use Mimey\MimeTypes;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\HasMedia;
use Owowagency\LaravelMedia\Rules\IsBase64;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Owowagency\LaravelMedia\Exceptions\UploadException;
use Owowagency\LaravelMedia\Rules\Concerns\ValidatesBase64;

class MediaManager
{
    use ValidatesBase64;

    /**
     * Upload media without specifying the type. Currently this method can
     * upload a string or an array of strings (base64 string).
     *
     * @param  \Spatie\MediaLibrary\HasMedia  $model
     * @param  string|array  $media
     * @param  string|null  $name
     * @param  string  $collection
     * @param  array  $customProperties
     * @return Media[]
     *
     * @throws \Owowagency\LaravelMedia\Exceptions\UploadException
     */
    public function upload(
        HasMedia $model,
        $media,
        string $name = null,
        string $collection = 'default',
        array $customProperties = []
    ): array {
        if (is_array($media)) {
            $uploads = [];

            if (Arr::isAssoc($media)) {
                return $this->upload(
                    $model,
                    ...$this->getUploadParams($media),
                );
            }

            foreach ($media as $value) {
                $uploads[] = $this->upload($model, $value, $name, $collection, $customProperties);
            }

            return Arr::flatten($uploads);
        } else {
            if (is_string($media)) {
                return [$this->uploadFromString($model, $media, $name, $collection, $customProperties)];
            }
        }

        throw new UploadException(gettype($media));
    }

    /**
     * Check if the given string is base64. If so, upload
     * the base64 string.
     *
     * @param  \Spatie\MediaLibrary\HasMedia  $model
     * @param  string  $string
     * @param  string|null  $name
     * @param  string  $collection
     * @param  array  $customProperties
     * @return \Spatie\MediaLibrary\MediaCollections\Models\Media
     *
     * @throws \Owowagency\LaravelMedia\Exceptions\UploadException
     */
    public function uploadFromString(
        HasMedia $model,
        string $string,
        $name = null,
        string $collection = 'default',
        array $customProperties = []
    ): Media {
        $base64Rule = new IsBase64();

        if ($base64Rule->passes('', $string)) {
            return $this->uploadFromBase64($model, $string, $name, $collection, $customProperties);
        } elseif (filter_var($string, FILTER_VALIDATE_URL) !== false) {
            return $this->uploadFromUrl($model, $string, $collection, $customProperties);
        }

        throw new UploadException();
    }

    /**
     * Saves base64 media to file and adds to collection.
     *
     * @param  \Spatie\MediaLibrary\HasMedia  $model
     * @param  string  $string
     * @param  string|null  $name
     * @param  string  $collection
     * @param  array  $customProperties
     * @return \Spatie\MediaLibrary\MediaCollections\Models\Media
     */
    public function uploadFromBase64(
        HasMedia $model,
        string $string,
        $name = null,
        string $collection = 'default',
        array $customProperties = []
    ): Media {
        $fileAdder = $model->addMediaFromBase64($string);

        preg_match('/data\:\w+\/(.*?)\;/s', $string, $extension);

        if (! is_null($name)) {
            if (empty($extension)) {
                $format = '%s.%s';

                $mimes = new MimeTypes();

                $extension[1] = $mimes->getExtension($this->getMimeType($string));
            } else {
                $format = '%s.%s';
            }

            $fileAdder->usingName($name)
                ->usingFileName(
                    sprintf(
                        $format,
                        $name,
                        $extension[1],
                    )
                );
        }

        $media = $fileAdder->toMediaCollection($collection);

        return $this->setCustomProperties($media, $customProperties);
    }

    /**
     * Saves url media to file and adds to collection.
     *
     * @param  \Spatie\MediaLibrary\HasMedia  $model
     * @param  string  $string
     * @param  string  $collection
     * @param  array  $customProperties
     * @return \Spatie\MediaLibrary\MediaCollections\Models\Media
     */
    public function uploadFromUrl(
        HasMedia $model,
        string $string,
        string $collection = 'default',
        array $customProperties = []
    ): Media {
        $media = $model->addMediaFromUrl($string)
            ->toMediaCollection($collection);

        return $this->setCustomProperties($media, $customProperties);
    }

    /**
     * Returns an array that matches the parameters needed to upload.
     *
     * @param  array  $data
     * @return array
     */
    public function getUploadParams(array $data): array
    {
        return array_values(Arr::only(
            $data,
            ['file', 'name', 'collection'],
        ));
    }

    /**
     * Set multiple custom properties to the media.
     */
    public function setCustomProperties(Media $media, array $customProperties = []): Media
    {
        foreach ($customProperties as $key => $value) {
            $media->setCustomProperty($key, $value);
        }

        return $media;
    }
}
