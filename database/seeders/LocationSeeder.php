<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = ['Pune', 'Lucknow', 'Kanpur', 'Bengaluru', 'California', 'Delhi', 'Bhubaneshwar', 'Gangtok', 'Darjeeling', 'Kolkata'];

        $this->command->info("Creating Locations : \n " . implode(", ", $locations) . "\n");
        foreach( $locations as $location )
        {
            Location::create([
                'name' => $location
            ]);
        }
    }
}
