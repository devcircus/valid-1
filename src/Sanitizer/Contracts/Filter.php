<?php

namespace PerfectOblivion\Valid\Sanitizer\Contracts;

/**
 * File copied from Waavi/Sanitizer https://github.com/waavi/sanitizer
 * Sanitization functionality to be customized within this project before a 1.0 release.
 */
interface Filter
{
    /**
     * Return the result of applying this filter to the given input.
     *
     * @param  mixed  $value
     *
     * @return mixed
     */
    public function apply($value, $options = []);
}
