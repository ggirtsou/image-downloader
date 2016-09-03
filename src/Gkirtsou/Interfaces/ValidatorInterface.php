<?php

namespace Gkirtsou\Interfaces;

/**
 * Interface ValidatorInterface
 * @package Gkirtsou\Interfaces
 */
interface ValidatorInterface
{
    /**
     * Checks if is conditions are met and all cases are valid.
     * @return bool
     */
    public function isValid() : bool;
}
