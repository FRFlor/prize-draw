<?php

namespace Tests\Feature;

use App\Applicant;
use App\User;
use Illuminate\Http\Response;
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

        $this->getJson(route('applicants.index'))
            ->assertJsonCount($allApplicants->count())
            ->assertJson($applicantNames);
    }

    public function testAnAuthenticatedUserCanUpdateAGivenApplicantName()
    {
        $user = factory(User::class)->create();
        $existingApplicant = factory(Applicant::class)->create();
        $newName = 'Jack Bauer is definitely not the initial name';

        $this
            ->actingAs($user)
            ->putJson(route('applicants.update', ['applicant' => $existingApplicant->id]), ['name' => $newName])
            ->assertSuccessful();

        $this->assertEquals($newName, $existingApplicant->fresh()->name, "The applicant's name was not updated");
    }

    public function testAGuestCannotUpdateAnApplicantsName()
    {
        $existingApplicant = factory(Applicant::class)->create();
        $newName = 'Jack Bauer is definitely not the initial name';

        $this
            ->putJson(route('applicants.update', ['applicant' => $existingApplicant->id]), ['name' => $newName])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAnApplicantsNameCannotBeSetToEmpty()
    {
        $user = factory(User::class)->create();
        $existingApplicant = factory(Applicant::class)->create();
        $newName = '';

        $this
            ->actingAs($user)
            ->putJson(route('applicants.update', ['applicant' => $existingApplicant->id]), ['name' => $newName])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
