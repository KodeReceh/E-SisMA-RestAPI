<?php

use Illuminate\Database\Seeder;
use App\Models\Villager;

class VillagersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Villager::class, 10)->create();
    }
}
