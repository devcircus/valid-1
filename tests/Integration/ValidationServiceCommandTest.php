<?php

namespace PerfectOblivion\Valid\Tests\Integration;

use Illuminate\Support\Facades\Artisan;

class ValidationServiceCommandTest extends IntegrationTestCase
{
    use TestsCommands;

    /** @test */
    public function bright_validation_command_makes_validaton_with_correct_methods()
    {
        Artisan::call('adr:validation', ['name' => 'MyValidationService']);

        include_once base_path().'/app/Services/MyValidationServiceValidation.php';

        $this->assertInstanceOf(\PerfectOblivion\Valid\ValidationService\ValidationService::class, app()->make(\App\Services\MyValidationServiceValidation::class));
        $this->assertMethodExists(\App\Services\MyValidationServiceValidation::class, 'rules');
        $this->assertMethodExists(\App\Services\MyValidationServiceValidation::class, 'filters');
    }
}
