<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class PermissionsController extends Controller
{
    /**
     * Returns all permission Roles.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index() {
        return Role::get();
    }

    /**
     * Updates a users permission role.
     * @param User $user
     * @param Request $request
     * @return User
     */
    public function update(Request $request, $id) {
        return User::find($id)
            ->syncRoles($request->user_group);
    }
}
