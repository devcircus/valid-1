<?php
/**
 * File copied from Waavi/Sanitizer https://github.com/waavi/sanitizer
 * Sanitization functionality to be customized within this project before a 1.0 release.
 */

namespace PerfectOblivion\Valid\Sanitizer\Laravel;

use Closure;
use InvalidArgumentException;
use PerfectOblivion\Valid\Sanitizer\Sanitizer;
use PerfectOblivion\Valid\Sanitizer\Contracts\Filter;

class Factory
{
    /** @var array */
    protected $customFilters;

    /**
     * Create a new Sanitizer factory instance.
     */
    public function __construct()
    {
        $this->customFilters = [];
    }

    /**
     * Create a new Sanitizer instance.
     *
     * @param  array  $data       Data to be sanitized
     * @param  array  $rules      Filters to be applied to the given data
     *
     * @return Sanitizer
     */
    public function make(array $data, array $rules)
    {
        $sanitizer = new Sanitizer($data, $rules, $this->customFilters);

        return $sanitizer;
    }

    /**
     *  Add a custom filters to all Sanitizers created with this Factory.
     *
     *  @param  string  $name  Name of the filter
     *  @param  mixed  $extension  Either the full class name of a Filter class implementing the Filter contract, or a Closure.
     *
     *  @throws InvalidArgumentException
     */
    public function extend($name, $customFilter)
    {
        if (empty($name) || ! is_string($name)) {
            throw new InvalidArgumentException('The Sanitizer filter name must be a non-empty string.');
        }

        if (! ($customFilter instanceof Closure) && ! in_array(Filter::class, class_implements($customFilter))) {
            throw new InvalidArgumentException('Custom filter must be a Closure or a class implementing the Waavi\Sanitizer\Contracts\Filter interface.');
        }

        $this->customFilters[$name] = $customFilter;
    }
}
