<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Storage;
use File;
use Image;

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
        // Return all if unspecified conditions.
        if ($request->input('client') || !$request->input()) {
            return User::get();
        }
        
        // Return the specified results.
        $columns = ['id', 'name', 'email'];
        $searchValue = $request->input('search');
        $query = User::orderBy($columns[$request->input('column')], $request->input('dir'));
        if ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%');
            });
        }
        $users = $query->paginate($request->input('length'));
        return ['data' => $users, 'draw' => $request->input('draw')];
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:6|max:255|confirmed',
            'profile_photo' => 'nullable|image64:jpeg,jpg,png',
        ]);

        if (!empty($request->profile_photo)) {
            // Create a new directory if it doesn't exist.
            $filepath = storage_path('app/public/profile_photos');
            if (!File::exists($filepath)) {
                File::makeDirectory($filepath);
            }

            // Convert Base64 -> png. Store and save.
            $image = Image::make($request->profile_photo);
            $profilePhoto = Storage::put('public/profile_photos/image_'. $id .'.png', $image->encode());
        }

        $user = User::where('id', $id)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'profile_photo' => 'profile_photos/image_'. $id .'.png',
            ]);

        return $user;
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
        return User::where('login_at', '>', Carbon::now()->subWeek())->count();
    }

    /**
     * Returns a count of the new users created within the past 2 weeks.
     *
     * @return integer
     */
    public function newUsers()
    {
        return User::where('created_at', '>', Carbon::now()->subWeeks(2))->count();
    }

    /**
     * Returns a count of users that are currently logged in.
     *
     * @return integer
     */
    public function online()
    {
        return User::where('login_at', '>', Carbon::now()->subHour())->count();
    }
}
