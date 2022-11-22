<?php

namespace Owowagency\LaravelMedia\Managers;

use Illuminate\Support\Arr;
use Mimey\MimeTypes;
use Owowagency\LaravelMedia\Exceptions\UploadException;
use Owowagency\LaravelMedia\Rules\Concerns\ValidatesBase64;
use Owowagency\LaravelMedia\Rules\IsBase64;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaManager
{
    use ValidatesBase64;

    /**
     * Upload media without specifying the type. Currently this method can
     * upload a string or an array of strings (base64 string).
     *
     * @throws FileIsTooBig
     * @throws FileCannotBeAdded
     * @throws UploadException
     */
    public function upload(
        HasMedia $model,
        array|string $files,
        string $name = '',
        string $collection = 'default',
        array $customProperties = [],
    ): array {
        if (is_array($files)) {
            $uploads = [];

            if (Arr::isAssoc($files)) {
                return $this->upload(
                    $model,
                    ...$this->getUploadParams($files),
                );
            }

            foreach ($files as $file) {
                $uploads[] = $this->upload($model, $file, $name, $collection, $customProperties);
            }

            return Arr::flatten($uploads);
        } elseif (is_string($files)) {
            $media = $this->uploadFromString($model, $files, $name)->toMediaCollection($collection);

            return [$this->setCustomProperties($media, $customProperties)];
        }

        throw new UploadException(gettype($files));
    }

    /**
     * Check if the given string is base64. If so, upload
     * the base64 string.
     *
     * @throws UploadException
     * @throws FileCannotBeAdded
     */
    protected function uploadFromString(HasMedia $model, string $string, string $name = ''): FileAdder
    {
        $base64Rule = new IsBase64();

        if ($base64Rule->passes('', $string)) {
            return $this->uploadFromBase64($model, $string, $name);
        } elseif (filter_var($string, FILTER_VALIDATE_URL) !== false) {
            return $this->uploadFromUrl($model, $string);
        }

        throw new UploadException();
    }

    /**
     * Saves base64 media to file and adds to collection.
     *
     * @throws FileCannotBeAdded
     */
    protected function uploadFromBase64(HasMedia $model, string $string, string $name = ''): FileAdder
    {
        $fileAdder = $model->addMediaFromBase64($string);

        preg_match('/data\:\w+\/(.*?)\;/s', $string, $extension);

        if (! empty($name)) {
            $format = '%s.%s';

            if (empty($extension)) {
                $extension[1] = (new MimeTypes)->getExtension($this->getMimeType($string));
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

        return $fileAdder;
    }

    /**
     * Saves url media to file and adds to collection.
     *
     * @throws FileCannotBeAdded
     */
    protected function uploadFromUrl(HasMedia $model, string $string): FileAdder
    {
        return $model->addMediaFromUrl($string);
    }

    /**
     * Returns an array that matches the parameters needed to upload.
     */
    protected function getUploadParams(array $data): array
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
