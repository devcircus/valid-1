<?php

namespace PerfectOblivion\Valid\Traits;

use PerfectOblivion\Valid\Sanitizer\Sanitizer;
use PerfectOblivion\Valid\Sanitizer\Laravel\SanitizesInput as BaseTrait;

trait SanitizesInput
{
    use BaseTrait;

    public function sanitizeData()
    {
        if ($this->filters()) {
            $sanitizer = new Sanitizer($this->all(), $this->filters());
            $this->replace($sanitizer->sanitize());
        }
    }
}
