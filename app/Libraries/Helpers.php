<?php

use Illuminate\Support\Facades\DB;
use App\Models as Model;
use Carbon\Carbon;

class Helpers
{
  public static function showFullColumn($table, array $columns)
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

  public static function translateDay($day, $long = false)
  {
    $translation = ['long' => $day, 'short' => substr($day, 0,3)];
    $format = $long ? 'long' : 'short';

    if (strcasecmp($day, "Sunday") == 0 || strcasecmp($day, "Sun") == 0) $translation = ['long' => 'Minggu', 'short' => "Min"];
    if (strcasecmp($day, "Monday") == 0 || strcasecmp($day, "Mon") == 0) $translation = ['long' => 'Senin', 'short' => "Sen"];
    if (strcasecmp($day, "Tuesday") == 0 || strcasecmp($day, "Tue") == 0) $translation = ['long' => 'Selasa', 'short' => "Sel"];
    if (strcasecmp($day, "Wednesday") == 0 || strcasecmp($day, "Wed") == 0) $translation = ['long' => 'Rabu', 'short' => "Rab"];
    if (strcasecmp($day, "Thursday") == 0 || strcasecmp($day, "Thu") == 0) $translation = ['long' => 'Kamis', 'short' => "Kam"];
    if (strcasecmp($day, "Friday") == 0 || strcasecmp($day, "Fri") == 0) $translation = ['long' => 'Jum\'at', 'short' => "Jum"];
    if (strcasecmp($day, "Saturday") == 0 || strcasecmp($day, "Sat") == 0) $translation = ['long' => 'Sabtu', 'short' => "Sab"];
    
    return $translation[$format];
  }

  public static function translateMonth($month, $long = false)
  {
    $format = $long ? 'long' : 'short';
    $translation = ['long' => $month, 'short' => substr($month, 0,3)];
    
    if ($month == "01" || strcasecmp($month, "January") == 0 || strcasecmp($month, "Jan") == 0) $translation = ['long' => 'Januari', 'short' => "Jan"];
    if ($month == "02" || strcasecmp($month, "February") == 0 || strcasecmp($month, "Feb") == 0) $translation = ['long' => 'Februari', 'short' => "Feb"];
    if ($month == "03" || strcasecmp($month, "March") == 0 || strcasecmp($month, "Mar") == 0) $translation = ['long' => 'Maret', 'short' => "Mar"];
    if ($month == "04" || strcasecmp($month, "April") == 0 || strcasecmp($month, "Apr") == 0) $translation = ['long' => 'April', 'short' => "Apr"];
    if ($month == "05" || strcasecmp($month, "May") == 0 || strcasecmp($month, "May") == 0) $translation = ['long' => 'Mei', 'short' => "Mei"];
    if ($month == "06" || strcasecmp($month, "June") == 0 || strcasecmp($month, "Jun") == 0) $translation = ['long' => 'Juni', 'short' => "Jun"];
    if ($month == "07" || strcasecmp($month, "July") == 0 || strcasecmp($month, "Jul") == 0) $translation = ['long' => 'Juli', 'short' => "Jul"];
    if ($month == "08" || strcasecmp($month, "August") == 0 || strcasecmp($month, "Aug") == 0) $translation = ['long' => 'Agustus', 'short' => "Agu"];
    if ($month == "09" || strcasecmp($month, "September") == 0 || strcasecmp($month, "Sep") == 0) $translation = ['long' => 'September', 'short' => "Sep"];
    if ($month == "10" || strcasecmp($month, "October") == 0 || strcasecmp($month, "Oct") == 0) $translation = ['long' => 'Oktober', 'short' => "Okt"];
    if ($month == "11" || strcasecmp($month, "November") == 0 || strcasecmp($month, "Nov") == 0) $translation = ['long' => 'November', 'short' => "Nov"];
    if ($month == "12" || strcasecmp($month, "December") == 0 || strcasecmp($month, "Dec") == 0) $translation = ['long' => 'Desember', 'short' => "Des"];
    return $translation[$format];
  }

  public static function translateDate($date, $dayName = false, $shortDay = false, $shortMon = false)
  {
    if(!$date) return null;
    $translation = '';
    $carbonDate = Carbon::parse($date);
    if($dayName) $translation .= Helpers::translateDay($carbonDate->format('l'), !$shortDay).', ';
    $translation .= $carbonDate->format('d').' '.Helpers::translateMonth($carbonDate->format('M'), !$shortMon).' '.$carbonDate->format('Y');
    return $translation;
  }
}