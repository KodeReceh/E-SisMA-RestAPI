<?php

use Illuminate\Support\Facades\DB;
use App\Models as Model;

class Helpers
{
  public static function showFullColumn($table, array $columns = null)
  {
    return DB::select(
      'SHOW FULL COLUMNS FROM ' . $table . ' ' . ($columns ? 'where FIELD IN (' . implode(',', $columns) . ')' : '')
    );
  }

  public static function generateLetterNumber($letterCodeId, $letterName)
  {
    $thisYear = date('Y');
    $letterCode = Model\LetterCode::getCode($letterCodeId);
    $lastOrdinal = 0;

    $last = Model\OutcomingLetter::join('letters', 'letters.id', '=', 'outcoming_letters.letter_id')
      ->whereYear('date', $thisYear)
      ->orderBy('ordinal', 'desc')
      ->select('ordinal')
      ->first();

    if ($last) $lastOrdinal = $last->ordinal; 

    $currentOrdinal = $lastOrdinal + 1;
    $acronym = preg_replace('/\b(\w)|./', '$1', $letterName);
    return $letterCode.'/'.$currentOrdinal.'/' . $acronym . '/' . $thisYear;
  }
}