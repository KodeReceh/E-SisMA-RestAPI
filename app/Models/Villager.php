<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Villager extends Model
{
    protected $table = 'villagers';

    protected $fillable = [
        'name',
        'birthplace',
        'job',
        'religion',
        'tribe',
        'NIK',
        'status',
        'address',
        'photo'
    ];

    protected $appends = [
        'birthdate_formatted',
        'religion_text',
        'sex_text',
        'tribe_text',
        'status_text',
        'select_name'
    ];

    public function getSelectNameAttribute()
    {
        return $this->name . ' - ' . $this->NIK;
    }

    public static function getFIelds()
    {
        $fields = [];

        foreach (config('esisma.villager_fields') as $field => $column) {
            $fields[] = [
                'id' => $field,
                'title' => ucwords(str_replace("_", " ", $field))
            ];
        }

        return $fields;
    }

    public function getBirthdateFormattedAttribute()
    {
        return \Helpers::translateDate($this->birthdate);
    }

    public function getSexTextAttribute()
    {
        return config('esisma.sexes')[$this->sex];
    }

    public function getReligionTextAttribute()
    {
        return config('esisma.religions')[$this->religion];
    }

    public function getTribeTextAttribute()
    {
        return config('esisma.tribes')[$this->tribe];
    }

    public function getStatusTextAttribute()
    {
        return config('esisma.villager_statuses')[$this->status];
    }
}
