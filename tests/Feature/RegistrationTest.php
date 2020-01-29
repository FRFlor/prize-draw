<?php

namespace Tests\Feature;

use App\Applicant;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    public function testAPersonCanSignUpForThePoll()
    {
        $this->withoutExceptionHandling();
        $this->postJson('/phpstorm/register', ['name' => 'Colin'])
            ->assertSuccessful();

        $this->assertNotEmpty(Applicant::query()->where('name', 'Colin')->first());
    }
}
