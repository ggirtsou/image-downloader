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

        $this->assertInstanceOf(ImageDownloader::class, new ImageDownloader($client, 1000));
    }
}
