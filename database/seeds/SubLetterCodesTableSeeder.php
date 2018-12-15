<?php

use Illuminate\Database\Seeder;
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
        $letterCodes = LetterCode::where('letter_code_id', null)->get();

        foreach ($letterCodes as $key => $value) {
            for ($i=0; $i < 7; $i++) { 
                $value->sub_letter_codes()->save(new LetterCode(['code' => $i+1, 'title' => 'Sub Letter Code ke-'.($i+1)]));
            }
        }
    }
}
