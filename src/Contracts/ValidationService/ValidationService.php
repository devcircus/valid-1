<?php

namespace PerfectOblivion\Valid\Contracts\ValidationService;

interface ValidationService
{
    /**
     * Validate the class instance.
     *
     * @param  array  $data
     */
    public function validate(array $data);
}
