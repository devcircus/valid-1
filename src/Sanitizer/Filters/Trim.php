<?php
/**
 * File copied from Waavi/Sanitizer https://github.com/waavi/sanitizer
 * Sanitization functionality to be customized within this project before a 1.0 release.
 */

namespace PerfectOblivion\Valid\Sanitizer\Filters;

use PerfectOblivion\Valid\Sanitizer\Contracts\Filter;

class Trim implements Filter
{
    /**
     * Trims the given string.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function apply($value, $options = [])
    {
        return is_string($value) ? trim($value) : $value;
    }
}
