<?php

namespace App\Services;
use App\Models\Role;


class RoleService
{
    protected $role;
    public function __construct(Role $role)
    {
        $this->role = $role;
    }
    public function getRole(){
        return $this->role->where('role_id', '!=', '1')->get();
    }
    
}