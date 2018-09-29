<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class PermissionsController extends Controller
{
    /**
     * Returns all permissions.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index() {
        return Permission::get();
    }

}
