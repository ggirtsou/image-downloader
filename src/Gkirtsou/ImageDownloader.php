<?php

namespace Gkirtsou;

use Gkirtsou\Interfaces\FileSystemInterface;
use Gkirtsou\Interfaces\ImageDownloaderInterface;
use GuzzleHttp\Client;

/**
 * Class ImageDownloader
 * @package Gkirtsou
 */
class ImageDownloader implements ImageDownloaderInterface
{
    /** @var Client Guzzle Client */
    private $client;

    /** @var int  0 = unlimited */
    private $maxSize = 0;

    /**
     * ImageDownloader constructor.
     * @param Client $client  Http Client
     * @param int    $maxSize Max allowed size
     */
    public function __construct(Client $client, int $maxSize)
    {
        $this->client = $client;
        $this->maxSize = $maxSize;
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function download(string $url, FileSystemInterface $saveTo) : bool
    {
        // TODO: Implement download() method.
    }
}
