<?php

use Illuminate\Database\Seeder;
use App\School;

class SchoolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        School::truncate();

        $faker = \Faker\Factory::create();

        $school_names = ['Marville University', 'Bradley University', 'Dequesne University', 'Rutgers', 'Northeastern University', 'University of Florida'];

        // And now, let's create a few schools in our database:
        for ($i = 0; $i < 6; $i++) {
            School::create([
                'name' => $school_names[$i],
                'id' => $i + 1
            ]);
        }

    }
}
