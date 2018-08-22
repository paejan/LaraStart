<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

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
}