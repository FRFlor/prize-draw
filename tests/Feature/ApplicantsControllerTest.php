<?php

namespace Tests\Feature;

use App\Applicant;
use Tests\TestCase;

class ApplicantsControllerTest extends TestCase
{
    public function testItCanProvideAListOfAllApplicantsNames()
    {
        $allApplicants = factory(Applicant::class, 15)->create();
        $applicantNames = $allApplicants->map(function ($applicant) {
            return [
                'id' => $applicant->id,
                'name' => $applicant->name
            ];
        })->toArray();

        $this->getJson('/applicants')
            ->assertJsonCount($allApplicants->count())
            ->assertJson($applicantNames);
    }
}
