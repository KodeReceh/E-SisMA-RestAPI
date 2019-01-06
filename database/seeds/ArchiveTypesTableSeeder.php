<?php

use Illuminate\Database\Seeder;

class ArchiveTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'Dinamis',
            'Statis',
            'Terjaga',
            'Umum'
        ];

        foreach ($types as $key => $type) {
            \App\Models\ArchiveType::create([
                'type' => $type
            ]);
        }
    }
}
