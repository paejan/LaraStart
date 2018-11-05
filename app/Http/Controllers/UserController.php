<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Returns all users based on the search criteria.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function index(Request $request)
    {
        // Return all users if request isn't a search.
        if (!$request->input()) {
            return User::with('Roles')
                ->get();
        }

        // Return the results based on the search criteria.
        $columns = ['id', 'name', 'email'];
        $users = User::with('Roles')
            ->orderBy($columns[$request->column], $request->dir)
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            })
            ->paginate($request->length);

        return ['data' => $users, 'draw' => $request->input('draw')];
    }

    /**
     * Creates a new user account.
     *
     * @param Request $request
     *
     * @return Collection
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'nullable|string|min:6|max:255|confirmed',
            'profile_photo' => 'nullable|image64:jpeg,jpg,png',
        ]);

        return User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'profile_photo' => $request->profile_photo,
        ]);
    }

    /**
     * Updates the user information.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Collection
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255',
            'password'      => 'nullable|string|min:6|max:255|confirmed',
            'profile_photo' => 'nullable|image64:jpeg,jpg,png',
        ]);

        return User::where('id', $id)
            ->when(!empty($request->password), function ($query) use ($request) {
                $query->update([
                    'password' => Hash::make($request->password),
                ]);
            })
            ->when(!empty($request->profile_photo), function ($query) use ($request) {
                $query->update([
                    'profile_photo' => $request->profile_photo,
                ]);
            })
            ->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);
    }

    /**
     * Returns a User with assigned roles by the specified id.
     *
     * @param int $id
     *
     * @return
     */
    public function show($id)
    {
        return User::with('Roles')
            ->findOrFail($id);
    }

    /**
     * Deletes a user by the specified id.
     *
     * @param User $user
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $user;
    }

    /**
     * Return a count of all registered users.
     *
     * @return int
     */
    public function count()
    {
        return User::count();
    }

    /**
     * Returns a count of the active users within the past week.
     *
     * @return int
     */
    public function active()
    {
        return User::active()->count();
    }

    /**
     * Returns a count of the new users created within the past 2 weeks.
     *
     * @return int
     */
    public function newUsers()
    {
        return User::new()->count();
    }

    /**
     * Returns a count of users that are currently logged in.
     *
     * @return int
     */
    public function online()
    {
        return User::online()->count();
    }

    /**
     * Updates a users permission role.
     *
     * @param Request $request
     * @param $id
     *
     * @return User
     */
    public function updateRole(Request $request, $id)
    {
        return User::find($id)
            ->syncRoles($request->user_group);
    }

    /**
     * Returns the signed in users profile data.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getProfile()
    {
        return Auth::user();
    }

    /**
     * Updates the authenticated users profile.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:191',
            'email'         => 'required|email|unique:users,email,'.Auth::user()->id.'|max:255',
            'password'      => 'nullable|string|min:6|max:255|confirmed',
            'profile_photo' => 'nullable|image64:jpeg,jpg,png',
        ]);

        return User::where('id', Auth::user()->id)
            ->when(!empty($request->password), function ($query) use ($request) {
                $query->update([
                    'password' => Hash::make($request->password),
                ]);
            })
            ->when(!empty($request->profile_photo), function ($query) use ($request) {
                $query->update([
                    'profile_photo' => $request->profile_photo,
                ]);
            })
            ->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);
    }
}
