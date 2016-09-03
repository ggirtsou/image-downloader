<?php

namespace Tests\Gkirtsou\Validator;

use Gkirtsou\Validator\ContentLengthValidator;

/**
 * Class ContentLengthValidatorTest
 * @package Tests\Gkirtsou\Validator
 */
class ContentLengthValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContentLengthValidator */
    private $class;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->class = new ContentLengthValidator();
    }

    /**
     * @return array
     */
    public function byteToMbDataProvider() : array
    {
        return [
            [1e+6, 1],
            [1.2e+7, 12],
            [5.034e+9, 5034],
            [1e+10, 10000],
        ];
    }

    /**
     * @dataProvider byteToMbDataProvider
     * @param float $kb
     * @param float $mb
     */
    public function testConvertSizeToMb(float $kb, float $mb)
    {
        $this->assertEquals($mb, $this->class->convertBytesToMb($kb));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage MaxSize or Content-Length is not set
     */
    public function testValidateMethodThrowsExceptionWhenMaxSizeNotSet()
    {
        $class = $this->class;
        $class->setContentLength(12345);
        $class->isValid();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage MaxSize or Content-Length is not set
     */
    public function testValidateMethodThrowsExceptionWhenContentLengthNotSet()
    {
        $class = $this->class;
        $class->setMaxSize(5);
        $class->isValid();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage MaxSize or Content-Length is not set
     */
    public function testValidateMethodThrowsExceptionWhenContentLengthAndMaxSizeAreNotSet()
    {
        $this->class->isValid();
    }

    /**
     * @return array
     */
    public function sizeDataProvider() : array
    {
        return [
            // maxsize (mb), contentLength (bytes), expected (bool)
            [5, 1e+7, false], // response 10mb = too large
            [10, 5e+6, true], // response 5mb = ok
            [1, 500000, false], // response 0.5mb = ok
        ];
    }

    /**
     * @dataProvider sizeDataProvider
     * @param float $maxSize
     * @param float $contentLength
     * @param bool $expected
     */
    public function testValidateMethod($maxSize, $contentLength, $expected)
    {
        $class = $this->class;
        $class
            ->setMaxSize($maxSize)
            ->setContentLength($contentLength);

        $this->assertEquals($expected, $class->isValid());
    }

    /**
     * @inheritdoc
     */
    public function tearDown()
    {
        $this->class = null;
    }
}
