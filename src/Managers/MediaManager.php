<?php

namespace Owowagency\LaravelMedia\Managers;

use Illuminate\Support\Arr;
use Mimey\MimeTypes;
use Owowagency\LaravelMedia\Exceptions\UploadException;
use Owowagency\LaravelMedia\Rules\Concerns\ValidatesBase64;
use Owowagency\LaravelMedia\Rules\IsBase64;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaManager
{
    use ValidatesBase64;

    /**
     * Upload media without specifying the type. Currently this method can
     * upload a string or an array of strings (base64 string).
     *
     * @param  array|string  $media
     *
     * @throws \Owowagency\LaravelMedia\Exceptions\UploadException
     *
     * @return Media[]
     */
    public function upload(
        HasMedia $model,
        $media,
        string $name = null,
        string $collection = 'default',
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
                $uploads[] = $this->upload($model, $value, $name, $collection);
            }

            return Arr::flatten($uploads);
        } else {
            if (is_string($media)) {
                return [$this->uploadFromString($model, $media, $name, $collection)];
            }
        }

        throw new UploadException(gettype($media));
    }

    /**
     * Check if the given string is base64. If so, upload
     * the base64 string.
     *
     * @param  null|string  $name
     *
     * @throws \Owowagency\LaravelMedia\Exceptions\UploadException
     */
    public function uploadFromString(
        HasMedia $model,
        string $string,
        $name = null,
        string $collection = 'default',
    ): Media {
        $base64Rule = new IsBase64();

        if ($base64Rule->passes('', $string)) {
            return $this->uploadFromBase64($model, $string, $name, $collection);
        } elseif (filter_var($string, FILTER_VALIDATE_URL) !== false) {
            return $this->uploadFromUrl($model, $string, $collection);
        }

        throw new UploadException();
    }

    /**
     * Saves base64 media to file and adds to collection.
     *
     * @param  null|string  $name
     */
    public function uploadFromBase64(
        HasMedia $model,
        string $string,
        $name = null,
        string $collection = 'default',
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
                    ),
                );
        }

        $media = $fileAdder->toMediaCollection($collection);

        return $media;
    }

    /**
     * Saves url media to file and adds to collection.
     */
    public function uploadFromUrl(
        HasMedia $model,
        string $string,
        string $collection = 'default',
    ): Media {
        return $model->addMediaFromUrl($string)
            ->toMediaCollection($collection);
    }

    /**
     * Returns an array that matches the parameters needed to upload.
     */
    public function getUploadParams(array $data): array
    {
        return array_values(Arr::only(
            $data,
            ['file', 'name', 'collection'],
        ));
    }
}
