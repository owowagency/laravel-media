<?php

namespace Owowagency\LaravelMedia\Managers;

use Mimey\MimeTypes;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\HasMedia;
use Owowagency\LaravelMedia\Rules\IsBase64;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Owowagency\LaravelMedia\Exceptions\UploadException;
use Owowagency\LaravelMedia\Rules\Concerns\GetsMimeTypeFromBase64;

class MediaManager
{
    use GetsMimeTypeFromBase64;

    /**
     * Determine how to upload media.
     *
     * @param  \Spatie\MediaLibrary\HasMedia  $model
     * @param  mixed  $media
     * @param  string|null  $name
     * @param  string  $collection
     * @return mixed
     *
     * @throws \Owowagency\LaravelMedia\Exceptions\UploadException
     */
    public static function upload(
        HasMedia $model,
        $media,
        string $name = null,
        string $collection = 'default'
    ) {
        if (is_array($media)) {
            $uploads = [];

            if (Arr::isAssoc($media)) {
                return static::upload(
                    $model,
                    ...static::uploadParamsFromArray($media),
                );
            }

            foreach ($media as $value) {
                $uploads[] = static::upload($model, $value);
            }

            return $uploads;
        } else {
            if (is_string($media)) {
                return static::uploadFromString($model, $media, $name, $collection);
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
     * @return \Spatie\MediaLibrary\MediaCollections\Models\Media
     *
     * @throws \Owowagency\LaravelMedia\Exceptions\UploadException
     */
    public static function uploadFromString(
        HasMedia $model,
        string $string,
        $name = null,
        string $collection = 'default'
    ): Media {
        $base64Rule = new IsBase64();

        if ($base64Rule->passes('', $string)) {
            return static::uploadFromBase64($model, $string, $name, $collection);
        } elseif (filter_var($string, FILTER_VALIDATE_URL) !== false) {
            return static::uploadFromUrl($model, $string);
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
     * @return \Spatie\MediaLibrary\MediaCollections\Models\Media
     */
    public static function uploadFromBase64(
        HasMedia $model,
        string $string,
        $name = null,
        string $collection = 'default'
    ): Media {
        $fileAdder = $model->addMediaFromBase64($string);

        preg_match('/data\:\w+\/(.*?)\;/s', $string, $extension);

        if (! is_null($name)) {
            if (empty($extension)) {
                $format = '%s.%s';

                $mimes = new MimeTypes();

                $extension[1] = $mimes->getExtension(static::getMimeType($string));
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

        return $media;
    }

    /**
     * Saves url media to file and adds to collection.
     *
     * @param  \Spatie\MediaLibrary\HasMedia  $model
     * @param  string  $string
     * @param  string  $collection
     * @return \Spatie\MediaLibrary\MediaCollections\Models\Media
     */
    public static function uploadFromUrl(
        HasMedia $model,
        string $string,
        string $collection = 'default'
    ): Media {
        return $model->addMediaFromUrl($string)
            ->toMediaCollection($collection);
    }

    /**
     * Returns an array that matches the parameters needed to upload.
     *
     * @param  array  $data
     * @return array
     */
    public static function uploadParamsFromArray(array $data): array
    {
        return array_values(Arr::only(
            $data,
            ['file', 'name', 'collection'],
        ));
    }
}
