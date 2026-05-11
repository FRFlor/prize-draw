<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class ApplicantsControllerTest extends TestCase
{
    public function testItCanProvideAListOfAllApplicantsNames()
    {
        $allApplicants = Applicant::factory()->count(15)->create();

        $this->getJson(route('applicants.index'))
            ->assertJsonCount($allApplicants->count())
            ->assertJsonStructure([['id', 'name']]);
    }

    public function testAnAuthenticatedUserCanUpdateAGivenApplicantName()
    {
        $user = User::factory()->create();
        $existingApplicant = Applicant::factory()->create();
        $newName = 'Jack Bauer is definitely not the initial name';

        $this->actingAs($user)
            ->putJson(route('applicants.update', ['applicant' => $existingApplicant->id]), ['name' => $newName])
            ->assertSuccessful();

        $this->assertEquals($newName, $existingApplicant->fresh()->name, "The applicant's name was not updated");
    }

    public function testAnAuthenticatedUserCanUpdateAGivenApplicantEmail()
    {
        $user = User::factory()->create();
        $existingApplicant = Applicant::factory()->create();
        $newEmail = 'jack@bauer.test';

        $this->actingAs($user)
            ->putJson(route('applicants.update', ['applicant' => $existingApplicant->id]), ['email' => $newEmail])
            ->assertSuccessful();

        $this->assertEquals($newEmail, $existingApplicant->fresh()->email, "The applicant's email was not updated");
    }

    public function testAGuestCannotUpdateAnApplicantsName()
    {
        $existingApplicant = Applicant::factory()->create();
        $newName = 'Jack Bauer is definitely not the initial name';

        $this->putJson(route('applicants.update', ['applicant' => $existingApplicant->id]), ['name' => $newName])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAnApplicantsNameCannotBeSetToEmpty()
    {
        $user = User::factory()->create();
        $existingApplicant = Applicant::factory()->create();
        $newName = '';

        $this->actingAs($user)
            ->putJson(route('applicants.update', ['applicant' => $existingApplicant->id]), ['name' => $newName])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testAGuestCannotCreateANewApplicantWithoutProvidingEmail()
    {
        $this->postJson(route('applicants.store'), ['name' => 'Foobar'])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testAGuestCanCreateANewApplicantWithAnUniqueEmail()
    {
        $participantAttributes = ['name' => 'Alex', 'email' => 'a.barry@vehikl.com'];
        $this->postJson(route('applicants.store'), $participantAttributes)->assertSuccessful();

        $this->assertNotEmpty(Applicant::query()->where($participantAttributes)->get());

        $participantAttributes = ['name' => 'Mr. Barry', 'email' => 'a.barry@vehikl.com'];
        $this->postJson(route('applicants.store'), $participantAttributes)->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testAnAuthenticatedUserCanCreateANewApplicantWithoutEmail()
    {
        $user = User::factory()->create();
        $newApplicantName = 'This is the new applicant name';

        $response = $this->actingAs($user)
            ->postJson(route('applicants.store'), ['name' => $newApplicantName]);

        $response->assertSuccessful();
        $response->assertJsonStructure(['id', 'name']);
        $response->assertJsonFragment(['name' => $newApplicantName]);
    }

    public function testAGuestCannotDeleteApplicants()
    {
        $targetApplicant = Applicant::factory()->create();

        $this->deleteJson(route('applicants.destroy', ['applicant' => $targetApplicant->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAnAuthenticatedUserCanDeleteAnExistingApplicant()
    {
        $user = User::factory()->create();
        $targetApplicant = Applicant::factory()->create();

        $this->actingAs($user)
            ->deleteJson(route('applicants.destroy', ['applicant' => $targetApplicant->id]))
            ->assertSuccessful();

        $this->assertEmpty(Applicant::query()->find($targetApplicant->id));
    }

    public function testIndexProvidesTheApplicantsEmailNameAndIdToAuthenticatedUsers()
    {
        $user = User::factory()->create();
        Applicant::factory()->count(20)->create();

        $this->actingAs($user)
            ->getJson(route('applicants.index'))
            ->assertSuccessful()
            ->assertJsonStructure([['email', 'name', 'id']]);
    }

    public function testIndexDoesNotProvideTheApplicantsEmailsToGuests()
    {
        Applicant::factory()->count(20)->create();

        $this->getJson(route('applicants.index'))
            ->assertSuccessful()
            ->assertDontSee('email');
    }
}
