<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birthplace',
        'birthdate',
        'api_token',
        'sex',
        'address',
        'handphone',
        'role_id',
        'status'
    ];

    protected $appends = ['permissions', 'birthdate_formatted'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'api_token'
    ];

    public function dispositions()
    {
        return $this->hasMany(Disposition::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function incoming_letters()
    {
        return $this->belongsToMany(IncomingLetter::class, 'dispositions', 'user_id', 'incoming_letter_id');
    }

    public static function getSelectOptions()
    {
        return User::select('id', 'name')->orderBy('name', 'asc')->get();
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'uploader_id');
    }

    public function getPermissionsAttribute()
    {
        if ($this->role)
            return $this->role->permissions()->pluck('can');
        
        return null;
    }

    public function getBirthdateFormattedAttribute()
    {
        return \Helpers::translateDate($this->birthdate);
    }

}
