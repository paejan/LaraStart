<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Returns all permission Roles.
     *
     * @param Request $request
     *
     * @return array|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Role[]
     */
    public function index(Request $request)
    {
        // Return all roles if request isn't a search.
        if (!$request->input()) {
            return Role::with('Users')->get();
        }

        // Return the results based on the search criteria.
        $columns = ['id', 'name'];
        $users = Role::with('Users')
            ->orderBy($columns[$request->column], $request->dir)
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%');
            })
            ->paginate($request->length);

        return ['data' => $users, 'draw' => $request->input('draw')];
    }

    /**
     * Creates a new user role and assigns the specified permission(s).
     *
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Model|Role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|unique:roles,name|string|max:191',
            'permissions.*' => 'required|integer|exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        // Un-assign/Assign Permissions.
        foreach (Permission::all() as $permission) {
            if (in_array($permission->id, $request->permissions)) {
                $role->givePermissionTo($permission->name);
            }
        }

        return $role;
    }

    /**
     * Updates a User Role.
     *
     * @param Request $request
     *
     * @return Role
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'          => 'required|unique:roles,name,'.$role->id.'|string|max:191',
            'permissions.*' => 'nullable|integer|exists:permissions,id',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        // Un-assign/Assign Permissions.
        foreach (Permission::all() as $permission) {
            if (in_array($permission->id, $request->permissions)) {
                if (!$role->hasPermissionTo($permission->name)) { // todo: does Laravel permissions already do this?
                    $role->givePermissionTo($permission->name);
                }
            } else {
                $role->revokePermissionTo($permission->name);
            }
        }

        return $role;
    }

    /**
     * Returns the Role with all of the assigned permissions.
     *
     * @param Role $id
     *
     * @return mixed
     */
    public function show($id)
    {
        return Role::with('permissions')
            ->findOrFail($id);
    }

    /**
     * Deletes the specified role by the associated role id.
     *
     * @param Role $role
     *
     * @throws \Exception
     *
     * @return Role
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return $role;
    }

    /**
     * Returns Role with all Users assigned specified by the role id.
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|Role|Role[]
     */
    public function roleUsers($id)
    {
        return Role::with('Users', 'Permissions')
            ->findOrFail($id);
    }

    /**
     * Returns Users without assigned user roles.
     *
     * @return mixed
     */
    public function usersWithoutRoles()
    {
        return User::doesntHave('Roles')->get();
    }
}
