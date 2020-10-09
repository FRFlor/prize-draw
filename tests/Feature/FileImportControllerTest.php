<?php

namespace Tests\Feature;

use App\Applicant;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileImportControllerTest extends TestCase
{
    use RefreshDatabase;



    public function testItCanPopulateTheApplicantsTableFromACsv()
    {
        $raffleAdmin = factory(User::class)->create();

        $applicantsInCsv = factory(Applicant::class, 2)->make()->toArray();
        $csvContent = "name,email\n{$applicantsInCsv[0]['name']},{$applicantsInCsv[0]['email']}\n{$applicantsInCsv[1]['name']},{$applicantsInCsv[1]['email']}";

        $this->actingAs($raffleAdmin)->post(route('import.csv'), [
            'applicants_csv' => UploadedFile::fake()->createWithContent('applicants.csv',$csvContent)
        ])->assertSuccessful();

        $applicantsInRaffle = Applicant::all();
        $this->assertCount(count($applicantsInCsv), $applicantsInRaffle);
        $this->assertEquals($applicantsInCsv[0]['name'], $applicantsInRaffle[0]['name']);
    }

    public function testItClearsPreviousApplicantsWhenAFileIsImported()
    {
        $raffleAdmin = factory(User::class)->create();
        $previousApplicantsInDatabase = factory(Applicant::class, 10)->create();

        $applicantsInCsv = factory(Applicant::class, 2)->make()->toArray();
        $csvContent = "name,email\n{$applicantsInCsv[0]['name']},{$applicantsInCsv[0]['email']}\n{$applicantsInCsv[1]['name']},{$applicantsInCsv[1]['email']}";

        $this->actingAs($raffleAdmin)->post(route('import.csv'), [
            'applicants_csv' => UploadedFile::fake()->createWithContent('applicants.csv',$csvContent)
        ])->assertSuccessful();

        $this->assertCount(count($applicantsInCsv), Applicant::all());
    }
}
