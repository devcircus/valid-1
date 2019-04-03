<?php

namespace PerfectOblivion\Valid\ValidationService\Concerns;

use stdClass;
use Illuminate\Support\Arr;

trait InteractsWithValidationData
{
    /**
     * Get all data under validation.
     *
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated()
    {
        $rules = $this->container->call([$this, 'rules']);

        return $this->only(collect($rules)->keys()->map(function ($rule) {
            return explode('.', $rule)[0];
        })->unique()->toArray());
    }

    /**
     * Get a subset containing the provided keys with values from the given data.
     *
     * @param  array|mixed  $keys
     *
     * @return array
     */
    public function only($keys)
    {
        $results = [];

        $input = $this->all();

        $placeholder = new stdClass;

        foreach (is_array($keys) ? $keys : func_get_args() as $key) {
            $value = data_get($input, $key, $placeholder);

            if ($value !== $placeholder) {
                Arr::set($results, $key, $value);
            }
        }

        return $results;
    }

    /**
     * Replaces the current parameters by a new set.
     *
     * @param  array  $parameters
     */
    public function replace(array $data)
    {
        $this->data = $data;
    }
}
