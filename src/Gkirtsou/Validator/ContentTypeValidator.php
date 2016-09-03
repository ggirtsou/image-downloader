<?php

namespace Gkirtsou\Validator;

use Gkirtsou\Exceptions\InvalidContentTypeException;
use Gkirtsou\Interfaces\ValidatorInterface;

/**
 * Class ContentTypeValidator
 * @package Gkirtsou\Validator
 */
class ContentTypeValidator implements ValidatorInterface
{
    /** @var string */
    private $contentType;

    /**
     * Set Content type
     * @param string $contentType
     */
    public function setContentType(string $contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @see https://www.ietf.org/rfc/rfc2045.txt section 5.1 Syntax of the Content-Type Header Field
     * @return bool
     */
    private function isContentTypeImage() : bool
    {
        $content = explode('/', $this->contentType);
        $discreteType = mb_strtolower($content[0], 'UTF-8');

        return ($discreteType === 'image');
    }

    /**
     * @inheritdoc
     * @throws InvalidContentTypeException
     * @return bool
     */
    public function isValid() : bool
    {
        if (empty($this->contentType)) {
            throw new InvalidContentTypeException('Content-Type must not be empty');
        }

        return $this->isContentTypeImage();
    }
}
