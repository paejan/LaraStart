<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
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
     * Creates a new user role.
     *
     * @param Request $request
     * @return Collection
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|unique:roles,name|string|max:191',
            'permissions.*' => 'required|exists:permissions,id',
        ]);

        return Role::create([
            'name' => $request->name,
        ]);
    }

    /**
     * Updates a User Role.
     *
     * @param Request $request
     * @return Collection
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'          => 'required|unique:roles,name,'.$role->id.'|string|max:191',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return $role;
    }

    /**
     * @param Role $id
     * @return mixed
     */
    public function show(Role $role) {
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
        return Role::with('Users')
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


    /**
     * Returns the permissions assigned the specified row.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|Role|Role[]
     */
    public function getPermissions($id) {
        return Role::with('Permissions')
                ->findOrFail($id);
    }
}
