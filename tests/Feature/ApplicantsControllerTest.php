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

        $this->actingAs($user)
            ->putJson(route('applicants.update', ['applicant' => $existingApplicant->id]), ['name' => $newName])
            ->assertSuccessful();

        $this->assertEquals($newName, $existingApplicant->fresh()->name, "The applicant's name was not updated");
    }

    public function testAGuestCannotUpdateAnApplicantsName()
    {
        $existingApplicant = factory(Applicant::class)->create();
        $newName = 'Jack Bauer is definitely not the initial name';

        $this->putJson(route('applicants.update', ['applicant' => $existingApplicant->id]), ['name' => $newName])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAnApplicantsNameCannotBeSetToEmpty()
    {
        $user = factory(User::class)->create();
        $existingApplicant = factory(Applicant::class)->create();
        $newName = '';

        $this->actingAs($user)
            ->putJson(route('applicants.update', ['applicant' => $existingApplicant->id]), ['name' => $newName])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testAGuestCannotCreateANewApplicant()
    {
        $this->postJson(route('applicants.store'), ['name' => 'Foobar'])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAnAuthenticatedUserCanCreateANewApplicant()
    {
        $user = factory(User::class)->create();
        $newApplicantName = 'This is the new applicant name';

        $response = $this->actingAs($user)
            ->postJson(route('applicants.store'), ['name' => $newApplicantName]);

        $response->assertSuccessful();
        $response->assertJsonStructure(['id', 'name']);
        $response->assertJsonFragment(['name' => $newApplicantName]);
    }

    public function testAGuestCannotDeleteApplicants()
    {
        $targetApplicant = factory(Applicant::class)->create();

        $this->deleteJson(route('applicants.destroy', ['applicant' => $targetApplicant->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAnAuthenticatedUserCanDeleteAnExistingApplicant()
    {
        $user = factory(User::class)->create();
        $targetApplicant = factory(Applicant::class)->create();

        $this->actingAs($user)
            ->deleteJson(route('applicants.destroy', ['applicant' => $targetApplicant->id]))
            ->assertSuccessful();

        $this->assertEmpty(Applicant::query()->find($targetApplicant->id));
    }
}
