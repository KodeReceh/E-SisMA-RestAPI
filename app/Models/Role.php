<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'title',
        'description'
    ];

    protected $appends = ['permission_ids'];

    public $timestamps = false;

    public function archives()
    {
        return $this->hasMany(Archive::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function getPermissionIdsAttribute()
    {
        return $this->permissions()->allRelatedIds();
    }

    public function syncPermissionsByName(...$permissions)
    {
        $permissionIds = Permission::whereIn('can', $permissions)->pluck('id');
        $this->permissions()->sync($permissionIds);
    }

    public function has($permission)
    {
        $has = $this->permissions()->where('can', $permission)->first();
        if ($has) return true;
        return false;
    }
}
