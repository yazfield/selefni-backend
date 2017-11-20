<?php
/**
 * Created by PhpStorm.
 * User: yzid
 * Date: 4/16/17
 * Time: 1:45 PM
 */

namespace app;

trait SingleMediaTrait
{
    public function setSingleMedia($value)
    {
        $this->clearMediaCollection();
        // FIXME: probably should check if url in a different way
        if (str_is('http', $value)) {
            $this->addMediaFromUrl($value)
                 ->toMediaCollection();
        } else {
            $this->addMedia($value)
                 ->toMediaCollection();
        }
    }

    public function getSingleMediaUrl()
    {
        $media = $this->getFirstMedia();
        if ($media) {
            return $media->getUrl();
        }

        return $this->getDefaultMediaUrl();
    }

    abstract public function getDefaultMediaUrl(): string;
}