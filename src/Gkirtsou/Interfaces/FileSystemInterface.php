<?php

namespace Gkirtsou\Interfaces;

/**
 * Interface FileSystemInterface
 * @package Gkirtsou\Interfaces
 */
interface FileSystemInterface
{
    /**
     * @param string $path     Full path to save file
     * @param string $saveName File name with extension
     * @return bool
     */
    public function saveTo(string $path, string $saveName);
}
