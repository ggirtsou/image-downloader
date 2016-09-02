<?php

namespace Gkirtsou\Interfaces;

/**
 * Interface ImageDownloaderInterface
 * @package Gkirtsou\Interfaces
 */
interface ImageDownloaderInterface
{
    /**
     * Downloads a remote image and saves it to file system.
     *
     * @param string              $url    Url to retrieve image from.
     * @param FileSystemInterface $saveTo File System interface
     * @return bool
     */
    public function download(string $url, FileSystemInterface $saveTo);
}
