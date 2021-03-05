<?php

namespace sisMR;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table='users';

    protected $primaryKey='id';
    
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasRoles($role)
    {
    	return $this->permisos === $role;
    }
}
