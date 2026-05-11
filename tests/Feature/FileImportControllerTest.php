<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileImportControllerTest extends TestCase
{
    use RefreshDatabase;



    public function testItCanPopulateTheApplicantsTableFromACsv()
    {
        $raffleAdmin = User::factory()->create();

        $applicantsInCsv = Applicant::factory()->count(2)->make()->toArray();
        $csvContent = "name,email\n{$applicantsInCsv[0]['name']},{$applicantsInCsv[0]['email']}\n{$applicantsInCsv[1]['name']},{$applicantsInCsv[1]['email']}";

        $this->actingAs($raffleAdmin)->post(route('import.csv'), [
            'applicants_csv' => UploadedFile::fake()->createWithContent('applicants.csv',$csvContent)
        ]);

        $applicantsInRaffle = Applicant::all();
        $this->assertCount(count($applicantsInCsv), $applicantsInRaffle);
        $this->assertEquals($applicantsInCsv[0]['name'], $applicantsInRaffle[0]['name']);
    }

    public function testItClearsPreviousApplicantsWhenAFileIsImported()
    {
        $raffleAdmin = User::factory()->create();
        $previousApplicantsInDatabase = Applicant::factory()->count(10)->create();

        $applicantsInCsv = Applicant::factory()->count(2)->make()->toArray();
        $csvContent = "name,email\n{$applicantsInCsv[0]['name']},{$applicantsInCsv[0]['email']}\n{$applicantsInCsv[1]['name']},{$applicantsInCsv[1]['email']}";

        $this->actingAs($raffleAdmin)->post(route('import.csv'), [
            'applicants_csv' => UploadedFile::fake()->createWithContent('applicants.csv',$csvContent)
        ]);

        $this->assertCount(count($applicantsInCsv), Applicant::all());
    }
}
