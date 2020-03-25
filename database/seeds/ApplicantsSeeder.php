<?php

use Illuminate\Database\Seeder;

class ApplicantsSeeder extends Seeder
{
    public function run()
    {
        $allNames = ["Alex Jones-Chick", "Brian Chaves", "Lucy Kim", "Alex Baletskyi", "David Gleba", "Chris Brown", "Nuno Souto", "Bob Bloom", "Seied Mohammad Hosein Abdoli", "Adam Brown", "Jeonghwan Ju", "Dave Redfern", "Albert Ilyes", "Reinhardt Christiansen"];

        $applicantData = collect($allNames)->map(function($name) {
            return [
                'name' => $name
            ];
        })->toArray();



        \App\Applicant::query()->insert($applicantData);
    }
}
