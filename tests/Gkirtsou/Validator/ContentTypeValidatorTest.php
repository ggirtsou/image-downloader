<?php

namespace Tests\Gkirtsou\Validator;

use Gkirtsou\Validator\ContentTypeValidator;

/**
 * Class ContentTypeValidatorTest
 */
class ContentTypeValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Gkirtsou\Interfaces\ValidatorInterface|ContentTypeValidator */
    private $class;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->class = new ContentTypeValidator();
    }

    /**
     * @return array
     */
    public function mimeTypeProvider() : array
    {
        return [
            ['image/jpg', true],
            ['video/subtype', false],
            ['application/json; charset=utf8', false],
            ['image/bmp; charset=utf-8', true],
        ];
    }

    /**
     * @param string $mimeType
     * @param bool   $expected
     * @dataProvider mimeTypeProvider
     */
    public function testImageContentTypeValidator(string $mimeType, bool $expected)
    {
        $this->class->setContentType($mimeType);
        $this->assertEquals($expected, $this->class->isValid());
    }

    /**
     * @inheritdoc
     */
    public function tearDown()
    {
        $this->class = null;
    }
}
