<?php

namespace Tests\Feature;

use App\Applicant;
use Tests\TestCase;

class HandoutTest extends TestCase
{
    public function testAPersonCanSignUpForThePoll()
    {
        $this->withoutExceptionHandling();
        $this->postJson('/register', ['name' => 'Colin'])
            ->assertSuccessful();

        $this->assertNotEmpty(Applicant::query()->where('name', 'Colin')->first());
    }

    public function testItCanProvideAListOfAllApplicantsNames()
    {
        $allApplicants = factory(Applicant::class, 15)->create();
        $applicantNames = $allApplicants->map(function ($applicant) {
            return $applicant->name;
        })->toArray();

        $this->getJson('/applicants')
            ->assertJsonCount($allApplicants->count())
            ->assertJson($applicantNames);
    }
}
