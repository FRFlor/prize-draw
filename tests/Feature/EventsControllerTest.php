<?php

namespace Tests\Feature;

use App\Applicant;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAnAuthenticatedUserCanAccessTheEventManagementDashboard()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)->get(route('event.dashboard'))->assertSuccessful();
    }

    public function testAGuestCannotAccessTheEventManagementDashboard()
    {
        $this->get(route('event.dashboard'))->assertRedirect(route('login'));
    }
}
