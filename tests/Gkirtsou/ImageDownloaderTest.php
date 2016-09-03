<?php

namespace Tests\Gkirtsou;

use Gkirtsou\ImageDownloader;

/**
 * Class ImageDownloaderTest
 */
class ImageDownloaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This test is just to make sure autoloading works
     * and everything is set up correctly.
     */
    public function testClassInitialization()
    {
        $client = $this->getMockBuilder('\GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $fileSystem = $this->getMockBuilder('\Psr\Http\Message\StreamInterface')
            ->getMock();

        $contentTypeValidator = $this->getMockBuilder('\Gkirtsou\Validator\ContentTypeValidator')
            ->disableOriginalConstructor()
            ->getMock();

        $contentLengthValidator = $this->getMockBuilder('\Gkirtsou\Validator\ContentLengthValidator')
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertInstanceOf(ImageDownloader::class, new ImageDownloader(
            $client,
            $fileSystem,
            $contentTypeValidator,
            $contentLengthValidator
        ));
    }

    // @todo add more tests
}
