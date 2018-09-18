<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function index(Request $request)
    {
        // Return all users if request isn't a search.
        if (!$request->input()) {
            return User::get();
        }

        // Return the specified results.
        $columns = ['id', 'name', 'email'];
        $users = User::orderBy($columns[$request->column], $request->dir)
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })
            ->paginate($request->length);

        return ['data' => $users, 'draw' => $request->input('draw')];
    }

    /**
     * Creates a new user account.
     * 
     * @param  Request  Request
     * @return Collection
     */
    public function store(Request $request) {
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
     * @param Integer $id
     * @return void
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
            ->update([
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'profile_photo' => $request->profile_photo,
        ]);
    }

    /**
     * Returns a users data by the specified id.
     *
     * @param  integer  $user
     * @return Collection
     */
    public function show(User $user)
    {
        return $user;
    }


    /**
     * Deletes a user by the specified id.
     *
     * @param  integer  $user
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
     * @return integer
     */
    public function count()
    {
        return User::count();
    }

    /**
     * Returns a count of the active users within the past week.
     *
     * @return integer
     */
    public function active()
    {
        return User::active()->count();
    }

    /**
     * Returns a count of the new users created within the past 2 weeks.
     *
     * @return integer
     */
    public function newUsers()
    {
        return User::new()->count();
    }

    /**
     * Returns a count of users that are currently logged in.
     *
     * @return integer
     */
    public function online()
    {
        return User::online()->count();
    }
}
