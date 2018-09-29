<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    /**
     * Returns all permission Roles.
     * @param Request $request
     * @return array|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Role[]
     */
    public function index(Request $request) {
        // Return all roles if request isn't a search.
        if (!$request->input()) {
            return Role::with('Users')->get();
        }

        // Return the results based on the search criteria.
        $columns = ['id', 'name'];
        $users = Role::with('Users')
            ->orderBy($columns[$request->column], $request->dir)
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate($request->length);

        return ['data' => $users, 'draw' => $request->input('draw')];
    }

}
