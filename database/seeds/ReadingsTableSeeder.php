<?php

use Illuminate\Database\Seeder;
use App\Reading;
use Carbon\Carbon;

class ReadingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        Reading::truncate();

        $date = Carbon::create(2011,01,01,00,00,00,'Singapore');
        $date = $date->addHours(9);

        foreach(range(1,3650) as $index)  
        {  
           	if($index%2 == 0)
            {
                $date = $date->addHours(7);
            }
            else if($index != 1 && ($index%2 != 0))
            {
                $date = $date->addHours(17);
            }

            DB::table('readings')->insert([  
                'parameter_id' => '1',  
                'reading_value' => $faker->numberBetween(15,35),  
                'user_id' => $faker->numberBetween(1,3),  
                'created_at' => $date,
                'updated_at' => $date 
            ]);  
        }
    }
}
