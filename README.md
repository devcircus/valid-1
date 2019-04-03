# Perfect Oblivion - Valid
### Custom Form Requests for Laravel

[![Latest Stable Version](https://poser.pugx.org/perfect-oblivion/valid/version)](https://packagist.org/packages/perfect-oblivion/valid)
[![Build Status](https://img.shields.io/travis/perfect-oblivion/valid/master.svg)](https://travis-ci.org/perfect-oblivion/valid)
[![Quality Score](https://img.shields.io/scrutinizer/g/perfect-oblivion/valid.svg)](https://scrutinizer-ci.com/g/perfect-oblivion/valid)
[![Total Downloads](https://poser.pugx.org/perfect-oblivion/valid/downloads)](https://packagist.org/packages/perfect-oblivion/valid)

![Perfect Oblivion](https://res.cloudinary.com/phpstage/image/upload/v1554128207/img/Oblivion.png "Perfect Oblivion")

### Disclaimer
The packages under the PerfectOblivion namespace exist to provide some basic functionality that I prefer not to replicate from scratch in every project. Nothing groundbreaking here.

## Package Objectives

### Form Requests
- Sanitization
   Laravel provides limited sanitization options, via middleware, out-of-the-box. For example, the [TrimStrings](https://github.com/laravel/framework/blob/5.6/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php) middleware is enabled by default. However, you'll be responsible for any other sanitization that needs to be performed on data from your users. [waavi/sanitizer](https://github.com/Waavi/Sanitizer) makes this a breeze. In form requests, it works similarly to validation. In the same way that the "rules" method returns an array of rules, the "filters" method should return an array of filters. This method has been added to your perfect-oblivion/valid FormRequest class by default . Here, just like with the 'rules' method, return an array of filters that you want to run on your data. See the available filters [here](https://github.com/Waavi/Sanitizer#available-filters), that you can use out-of-the-box. You can also create your own [custom filters](https://github.com/Waavi/Sanitizer#adding-custom-filters) to use.

### Custom Rules
I often use custom rules in Laravel. From time to time, I want access to the current FormRequest and/or the current validator, from within the custom rule. Anything can be passed through the constructor of the custom rule, however, it can get ugly passing the FormRequest, Validator and other data like this. See below:
```php
    public function rules()
    {
        return [
            'name' => ['required', 'size:2', new NotSpamRule($this, $this->getValidator())],
        ];
    }
```
With this package, the following will accomplish the same:
```php
    public function rules()
    {
        return [
            'name' => ['required', 'size:2', new NotSpamRule],
        ];
    }
```
It may not seem like much of an improvement, but I prefer my rules to be as clean and readable as possible.
> These custom rules can be used in your Form Requests or in a ValidationService class. See below.

### ValidationService
In my day-to-day development, I utilize a pattern similar to [Paul Jones']() [ADR pattern](http://paul-m-jones.com/archives/5970). Using this pattern, validating at the 'controller' level is discouraged. This is a function of the domain. It is recommended to perform any authorization as soon as possible (I prefer to authorize via middleware, using Laravel's built-in Gates/Policies). Data can then be retrieved from the request and passed to a service to be used for further data retrieval, manipulation, etc. This is part of the domain layer. Using a ValidationService, you can validate the data before the service starts working on it.

The ValidationService draws HEAVILY from Laravel's Form Requests and operates in a similar way, without the Authorization component. More is explained below in the 'Usage' section.

## Installation
You can install the package via composer. From your project directory, in your terminal, enter:
```bash
composer require perfect-oblivion/valid
```

In Laravel > 5.6.0, the ServiceProvider will be automtically detected and registered.
If you are using an older version of Laravel, add the package service provider to your config/app.php file, in the 'providers' array:
```php
'providers' => [
    //...
    PerfectOblivion\Valid\ValidServiceProvider::class,
    //...
];
```

Then, if you would like to change any of the configuration options, run:
```bash
php artisan vendor:publish
```
and choose the PerfectOblivion/Valid option.

This will copy the package configuration (valid.php) to your 'config' folder.
Here, you can set the namespace and suffix for your FormRequests, ServiceValidation classes Custom Rules:

```php
<?php

return [
    'requests' => [
        /*
        |--------------------------------------------------------------------------
        | Namespace
        |--------------------------------------------------------------------------
        |
        | Set the namespace for the Custom Form Requests.
        |
        */
        'namespace' => 'Http\\Requests',

        /*
        |--------------------------------------------------------------------------
        | Suffix
        |--------------------------------------------------------------------------
        |
        | Set the suffix to be used when generating Custom Form Requests.
        |
        */
        'suffix' => 'Request',

        /*
        |--------------------------------------------------------------------------
        | Duplicate Suffixes
        |--------------------------------------------------------------------------
        |
        | If you have a Request suffix set and try to generate a Request that also includes the suffix,
        | the package will recognize this duplication and rename the Request to remove the suffix.
        | This is the default behavior. To override and allow the duplication, change to false.
        |
        */
        'override_duplicate_suffix' => true,
    ],
    'rules' => [
        /*
        |--------------------------------------------------------------------------
        | Namespace
        |--------------------------------------------------------------------------
        |
        | Set the namespace for the custom rules.
        |
     */
        'namespace' => 'Rules',

        /*
        |--------------------------------------------------------------------------
        | Suffix
        |--------------------------------------------------------------------------
        |
        | Set the suffix to be used when generating custom rules.
        |
         */
        'suffix' => 'Rule',

        /*
        |--------------------------------------------------------------------------
        | Duplicate Suffixes
        |--------------------------------------------------------------------------
        |
        | If you have a Rule suffix set and try to generate a Rule that also includes the suffix,
        | the package will recognize this duplication and rename the Rule to remove the suffix.
        | This is the default behavior. To override and allow the duplication, change to false.
        |
        */
        'override_duplicate_suffix' => true,
    ],
    'validation-services' => [
        /*
        |--------------------------------------------------------------------------
        | Namespace
        |--------------------------------------------------------------------------
        |
        | Set the namespace for the validation services.
        |
     */
        'namespace' => 'Services',

        /*
        |--------------------------------------------------------------------------
        | Suffix
        |--------------------------------------------------------------------------
        |
        | Set the suffix to be used when generating validation services.
        |
         */
        'suffix' => 'Validation',

        /*
        |--------------------------------------------------------------------------
        | Duplicate Suffixes
        |--------------------------------------------------------------------------
        |
        | If you have a Validation suffix set and try to generate a Validation that also includes the suffix,
        | the package will recognize this duplication and rename the Validation to remove the suffix.
        | This is the default behavior. To override and allow the duplication, change to false.
        |
        */
        'override_duplicate_suffix' => true,
    ],
];

```

## Usage

### Form Requests
To generate a FormRequest class, run the following command:
```bash
php artisan adr:request CreateComment
```

Using the default suffix option of "Request" and the default namespace option of "Http\\Requests", this command will generate an "App\Http\Requests\CreateCommentRequest" class.
> Note: If you have a suffix set in the config, for example: "Request", and you run the following command:
```bash
php artisan adr:request CreateCommentRequest
```
> The suffix will NOT be duplicated. To turn off this suffix-duplication detection, change the "override_duplicate_suffix" option to false.

Below is an example Custom Form Request class:
```php
<?php

namespace App\Http\Requests;

use PerfectOblivion\Valid\BaseRequest;

class CustomClass extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
        ];
    }

    /**
     * Get the sanitization filters that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'name' => 'uppercase',
        ];
    }
}
```
> Note: For any pre-validation logic that is necessary, you can utilize the ```beforeValidation()``` method. There is also a ```transform()``` method that can be used to manipulate the data before validation. Check out PerfectOblivion\Valid\BaseRequest for more details.

### Custom Rules
To generate a custom Rule, run the following command:
```bash
php artisan adr:rule CustomRule
```

Using the default suffix option of "Rule" and the default namespace option of "Rules", this command will generate an "App\Rules\CustomRule" class.
> Note: If you have a suffix set in the config, for example: "Rule", and you run the following command:
```bash
php artisan adr:rule CustomRule
```
> The suffix will NOT be duplicated. To turn off this suffix-duplication detection, change the "override_duplicate_suffix" option to false.

From time to time, you may need to access information from the current FormRequest and/or current validator inside your CustomRule classes. All custom rules come with the current FormRequest object and the current validator object attached. These can be referenced inside the CustomRule class via their properties. See below:
```php
<?php

namespace App\Rules;

use PerfectOblivion\Valid\CustomRule;

class NotSpamRule extends CustomRule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // if needed, access the current Validator with $this->validator and the current FormRequest with $this->request

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}

```

### Validation Service
> If you are using Form Requests, it is not necessary to also use a Validation Service. Form Requests are designed to be used by your controllers, retrieving the data to be validated directly from the current request. A Validation Service is used to validate data from other classes within your domain.

To generate a validation service, run the following command:
```bash
php artisan adr:validation StoreCommentValidation
```

Using the default suffix option of "Validation" and the default namespace option of "Services", this command will generate an "App\Services\StoreCommentValidation" class.
> Note: If you have a suffix set in the config, for example: "Validation", and you run the following command:
```bash
php artisan adr:validation StoreCommentValidation
```
> The suffix will NOT be duplicated. To turn off this suffix-duplication detection, change the "override_duplicate_suffix" option to false.

Currently, the Validation Service must be resolved from the container. Then you call the ```validate()``` method, passing in an array of data to be validated. Using any class in Laravel that automatically resolves classes from the container, you can use a Validation Service as outlined below. Here, I am using a Service class from [PerfectOblivion\Services](https://github.com/perfect-oblivion/services) class, however, it will work within any of Laravel's components that auto-resolves classes, such as Events, Jobs, etc.:
```php
<?php

namespace App\Services;

use App\Comment;
use PerfectOblivion\Services\Traits\SelfCallingService;

class StoreCommentService
{
    use SelfCallingService;

    protected $params;

    /**
     * Construct a new StoreCommentService.
     */
    public function __construct(StoreCommentValidator $validator) // here, the validation service class is resolved from the container
    {
        $this->validator = $validator;
    }

    /**
     * Handle the call to the service.
     *
     * @return mixed
     */
    public function run($params)
    {
        $validated = $validator->validate($params); // we call the validate method, passing the array of parameters

        return Comment::create([
            'content' => $validated['content'], // the validated method returns the key/value pairs of data that was validaated
            'user_id' => auth()->user()->id,
        ]);
    }
}
```
> Just as with Laravel's Form Requests, if the data, doesn't pass validation, you are redirected back to the form with the errors. The validation exception logic and redirect logic is customizable from within the ValidationService class, just like it is within Form Requests.

Below is a sample Validation Service class:
```php
<?php

namespace App\Services;

use PerfectOblivion\Services\Validation\ValidationService;

class StoreCommentValidator extends ValidationService
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', new NotSpamRule()],
        ];
    }

    /**
     * Get the filters to apply to the data.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'name' => 'uppercase',
        ];
    }
}
```

> You can use our custom rules and the waavi/sanitizer filters within a ValidationService just like you can with the Custom FormRequests. Also, in the same way that CustomRules in FormRequests give you access to $request and $validator properties, the CustomRules used in the ValidationService has $validator and $service properties that give you access to the current Validator and the current ValidationService.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email clay@phpstage.com instead of using the issue tracker.

## Roadmap

We plan to work on flexibility/configuration soon, as well as release a framework agnostic version of the package.

## Credits

- [Clayton Stone](https://github.com/devcircus)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
