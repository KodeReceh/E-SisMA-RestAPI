<?php

use Illuminate\Support\Facades\DB;

class Helpers {
  public static function showFullColumn($table, Array $columns = null){
    return DB::select(
      'SHOW FULL COLUMNS FROM '.$table.' '.($columns ? 'where FIELD IN ('.implode(',', $columns).')' : '')
    );
  }
}