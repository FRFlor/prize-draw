<?php

use Illuminate\Database\Seeder;

class ApplicantsSeeder extends Seeder
{
    public function run()
    {
        $allNames = ['Aare Pikaro', 'Aaron Freebern', 'aaron freebern ', 'Aaron Queen', 'Adam Schwartz', 'Addan Jorgensen', 'Adrian Preston', 'Alex Cushing', 'Alexander Middeleer', 'Andres Mauricio Santaella', 'Andrew Carey', 'Andrey Levin', 'Andy Rather', 'Angel Marino', 'Anthony Failla', 'Ashley Sullivan', 'Austin Akers', 'Ben Potter', 'Benn Mcguire', 'Bill Artz', 'Bill Pirkle', 'Brad Balfour', 'Brad Efting', 'Brandon Mork', 'Brandon Rubenstein', 'Brent De Lara', 'Brett Mcdade', 'Brian Eisenman', 'Bryce Pearce', 'Carleigh Card', 'Carlo Pati', 'Casey Trombley', 'Chad Packer', 'Charles Sterling', 'Chase Giunta', 'Chris Andrikanich', 'Chris Jaynes', 'Chris Moses', 'Chris Porter', 'Christoph Eberl', 'Colby Holt', 'Collin Welker', 'Craig  Harshbarger', 'Cristina Kernan', 'Curt Houghton', 'Damian Adams', 'Daniel Hopkins', 'Daniel Olsen', 'Daniel Owens', 'Darius Pilapil', 'David Horton', 'Diana Alvarez', 'Doug Davies', 'Drew Rawitz', 'Dryden Long', 'Eduardo Perez', 'Elizabeth Portilla', 'Elliott Groves', 'Emeka Nweze', 'Eric Agnew', 'Eric Jarvis', 'Erich Fuchs', 'Erik Hanchett', 'Ethan Wolz', 'Evan Sparkman', 'Fabian Kremer', 'Fernando Mendoza Olivares', 'Flint Johnston', 'Flor Banos Gaspar', 'Francesca Rachele Buratti', 'Geoffrey Jones', 'George Sarantinos', 'Gian Brazzini', 'Greg Crosby', 'Harrison Wallin', 'Heather Hill', 'Hector Romero', 'Israel Karasek', 'Jace Harmer', 'Jairo Suriel', 'Jason Land', 'Jason Leisch', 'Jay Hostetter', 'Jeff Horner', 'Jesus Hernandez', 'Jim Wigginton', 'Joel Brubaker', 'John Paul Bamberg', 'Jon Sheppard', 'Jordan Brady', 'Jose Garrido', 'Jose Lopez', 'Josh Anderson', 'Josh Huber', 'Josh Taylor', 'Joshua Brown', 'Joshua Tellez', 'Julianne Loria', 'Justin Boyson', 'Justin Jones', 'Kavya Guntupally', 'Kayla Peace', 'Kayla Webb', 'Keith Walker', 'Ken Hogan', 'Kenn Trezek', 'Kerry Huguet', 'Kori Ryter', 'Lance Faler', 'Leandro Correia Gomes De Matos', 'Lee Marlow', 'Lee Warrick', 'Mantas Andrijauskas', 'Mark Fasel', 'Martin Rouse', 'Mason Terry', 'Matt Guillet', 'Matt White', 'Matthew Evanoff', 'Matthew Jones', 'Matthew Kindness', 'Mattie Kenny', 'Michael Bowen', 'Michael Crow', 'Michael Nannola', 'Michael Savastano', 'Michael Young', 'Michel Paupulaire', 'Mike Fritzsche', 'Miles Aylward', 'Miquelyn Hollingsworth', 'Mitchell Cox', 'Moriel Schottlender', 'Mykola Koval', 'Nate Rabins', 'Nick Arocho', 'Nick Ronnei', 'Nicolas Ries', 'Nicolas Tinte', 'Oleksandr Havrashenko', 'Paige Kelley', 'Paige Kelley', 'Patrick Etienne', 'Philip Double', 'Phillip Ells', 'Rajesh Gubba', 'Rebecca Peltz', 'Richard Paez', 'Ricky Brown', 'Robert Zavala', 'Ross Baker', 'Ryan Christensen', 'Ryan Gilligan', 'Ryan Grant', 'Ryan Knepper', 'Sabrina Iqbal', 'Samuel Cavedo', 'Scott Butts', 'Scott Carlson', 'Scott Henscheid', 'Scott Ruffing', 'Scott Schaefer', 'Seth Kline', 'Shane Church', 'Shea Cole', 'Stephen Tran', 'Sunjay Lal', 'Teresa Marban Castro', 'Tim Butler', 'Timon Feldmann', 'Tobias Lasco', 'Tolu Abayomi', 'Tom Workman', 'Tony Mao', 'Vagif Ziyadov', 'Vanessa Alvarez', 'Vera Goerisch', 'Vincent De Caster', 'Will Nation', 'Yuen Wing Kam', 'Zach Case', 'Zakary Stone', 'Zam Montoya', 'John Mcandrews'];

        $applicantData = collect($allNames)->map(function($name) {
            return [
                'name' => $name
            ];
        })->toArray();



        \App\Applicant::query()->insert($applicantData);
    }
}
