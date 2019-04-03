<?php

namespace PerfectOblivion\Valid\ValidationService;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use PerfectOblivion\Valid\Traits\SanitizesInput;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use PerfectOblivion\Valid\ValidationService\Concerns\HandlesRedirects;
use PerfectOblivion\Valid\Traits\PreparesCustomRulesForServiceValidator;
use PerfectOblivion\Valid\ValidationService\Concerns\InteractsWithValidationData;
use PerfectOblivion\Valid\Contracts\ValidationService\ValidationService as Contract;

class ValidationService implements Contract
{
    use HandlesRedirects, InteractsWithValidationData, SanitizesInput, PreparesCustomRulesForServiceValidator;

    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'default';

    /**
     * The data to be validated.
     *
     * @var array
     */
    protected $data;

    /**
     * Validate the class instance.
     *
     * @param  array  $data
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return array
     */
    public function validate(array $data)
    {
        $this->data = $data;
        $this->prepareCustomRules();

        $this->prepareForValidation();

        $validator = $this->getValidator();

        if (! $validator->passes()) {
            $this->failedValidation($validator);
        }

        return $this->validated();
    }

    /**
     * Get the validator for the request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidator()
    {
        $factory = $this->container->make(ValidationFactory::class);
        $validator = $this->container->call([$this, 'validator'], compact('factory'));

        if (method_exists($this, 'withValidator')) {
            $this->withValidator($validator);
        }

        return $validator;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * Create the default validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Factory  $factory
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(ValidationFactory $factory)
    {
        return $factory->make(
            $this->validationData() ?? [],
            $this->container->call([$this, 'rules']),
            $this->messages(),
            $this->attributes()
        );
    }

    /**
     * Run any needed logic prior to validation.
     */
    protected function prepareForValidation()
    {
        $this->sanitizeData();

        $this->beforeValidation();

        return $this->validationData();
    }

    /**
     * Run any logic needed prior to validation running.
     */
    protected function beforeValidation()
    {
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        return $this->data;
    }

    /**
     * Transform the data if necessary.
     *
     * @param  array  $data
     *
     * @return array
     */
    protected function transform($data)
    {
        return $data;
    }

    /**
     * Set the container implementation.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     *
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }
}
