<?php
/**
 * File copied from Waavi/Sanitizer https://github.com/waavi/sanitizer
 * Sanitization functionality to be customized within this project before a 1.0 release.
 */

namespace PerfectOblivion\Valid\Sanitizer\Filters;

use PerfectOblivion\Valid\Sanitizer\Contracts\Filter;

class Uppercase implements Filter
{
    /**
     * Uppercase the given string.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function apply($value, $options = [])
    {
        return is_string($value) ? strtoupper($value) : $value;
    }
}
