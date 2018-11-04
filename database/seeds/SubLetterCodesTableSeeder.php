<?php

use Illuminate\Database\Seeder;
use App\Models\SubLetterCode;
use App\Models\LetterCode;

class SubLetterCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $letterCodes = LetterCode::all();

        foreach ($letterCodes as $key => $value) {
            for ($i=0; $i < 7; $i++) { 
                $value->sub_letter_codes()->save(new SubLetterCode(['code' => $key+1, 'title' => 'Sub Letter Code ke-'.($key+1)]));
            }
        }
    }
}
