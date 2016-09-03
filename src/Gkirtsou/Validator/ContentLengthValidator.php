<?php

namespace Gkirtsou\Validator;

use Gkirtsou\Interfaces\ValidatorInterface;

/**
 * Class ContentLengthValidator
 * @package Gkirtsou\Validator
 */
class ContentLengthValidator implements ValidatorInterface
{
    /** @var int */
    private $maxSize;

    /** @var int */
    private $contentLength;

    /**
     * Set max size
     *
     * @param int $maxSize
     * @return ContentLengthValidator
     */
    public function setMaxSize(int $maxSize) : ContentLengthValidator
    {
        $this->maxSize = $maxSize;

        return $this;
    }

    /**
     * Set Content Length
     *
     * @param int $contentLength
     * @return ContentLengthValidator
     */
    public function setContentLength(int $contentLength) : ContentLengthValidator
    {
        $this->contentLength = $contentLength;

        return $this;
    }

    /**
     * Checks if size is too big or not.
     *
     * @return bool
     * @throws \Exception if maxSize or contentLength are empty
     */
    public function isValid() : bool
    {
        if (null === $this->maxSize || null === $this->contentLength) {
            throw new \Exception('MaxSize or Content-Length is not set');
        }

        return $this->isSizeAcceptable();
    }

    /**
     * Converts Bytes to MB.
     *
     * @param float $size
     * @return float
     */
    public function convertBytesToMb(float $size) : float
    {
        return round($size / (1000 * 1000));
    }

    /**
     * Check if size is not too big. This check is based on Content-Length response header.
     * @return bool
     */
    protected function isSizeAcceptable()
    {
        return ($this->convertBytesToMb($this->contentLength) < $this->maxSize);
    }
}
