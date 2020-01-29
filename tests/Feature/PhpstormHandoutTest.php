<?php

namespace Tests\Feature;

use App\Applicant;
use Tests\TestCase;

class PhpstormHandoutTest extends TestCase
{
    public function testAPersonCanSignUpForThePoll()
    {
        $this->postJson('/phpstorm/register', ['name' => 'Colin'])
            ->assertSuccessful();

        $this->assertNotEmpty(Applicant::query()->where('name', 'Colin')->first());
    }

    public function testItCanProvideAListOfAllApplicantsNames()
    {
        $allApplicants = factory(Applicant::class, 15)->create();
        $applicantNames = $allApplicants->map(function ($applicant) {
            return $applicant->name;
        })->toArray();

        $this->getJson('/phpstorm/applicants')
            ->assertJsonCount($allApplicants->count())
            ->assertJson($applicantNames);
    }
}
