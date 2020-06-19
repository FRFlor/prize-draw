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

        $this->getJson(route('applicants.index'))
            ->assertJsonCount($allApplicants->count())
            ->assertJsonStructure([['id', 'name']]);
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

    public function testIndexProvidesTheApplicantsEmailNameAndIdToAuthenticatedUsers()
    {
        $user = factory(User::class)->create();
        factory(Applicant::class, 20)->create();

        $this->actingAs($user)
            ->getJson(route('applicants.index'))
            ->assertSuccessful()
            ->assertJsonStructure([['email', 'name', 'id']]);
    }

    public function testIndexDoesNotProvideTheApplicantsEmailsToGuests()
    {
        factory(Applicant::class, 20)->create();

        $this->getJson(route('applicants.index'))
            ->assertSuccessful()
            ->assertDontSee('email');
    }
}
