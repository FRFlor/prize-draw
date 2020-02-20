<?php

use Illuminate\Database\Seeder;

class ApplicantsSeeder extends Seeder
{
    public function run()
    {
        $allNames = [
            'Barry Shantz',
            'Bohdana Tyshchenko',
            'Charlie Dobson',
            'Chuck Taylor',
            'Daniel Dorsey',
            'David Gleba',
            'Dima Tkachuk',
            'Elias Roro',
            'Howard',
            'Humaira Siddiqa',
            'John Oepkes',
            'Justin Struk',
            'Kevin Quinn',
            'Kyle Siopiolosz',
            'Lerina J-Y Razafy',
            'Mazher Mehboob',
            'Murat Dikmen',
            'Nahej Lefebvre',
            'Pranay  Sheth',
            'preeti minhas',
            'Reinhardt Christiansen',
            'Salman Ahmad',
            'Sasha Alex Romanenko',
            'sayani chowdhury',
            'Sean Doyle',
            'Seied Mohammad Hosein Abdoli',
            'Sonal Chauhan',
            'Sukhjeevan Kaur',
            'Sukhpreet Gill',
            'Tanya Mittal',
        ];

        $applicantData = collect($allNames)->map(function($name) {
            return [
                'name' => $name
            ];
        })->toArray();



        \App\Applicant::query()->insert($applicantData);
    }
}
