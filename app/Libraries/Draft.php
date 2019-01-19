<?php
namespace App\AdditionalClass;

class Draft
{
  public function __construct(...$props)
  {
    foreach ($props as $key => $value) {
      foreach ($value as $k => $v) {
        $this->$k = $v;
      }
    }
  }
}
