<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('country')
            ->when(request()->filled('name'), function ($query) {
                $query->where('name', 'LIKE', '%' . request('name') . '%');
            })
            ->when(request()->filled('about'), function($query) {
                $query->where('about', 'LIKE', '%' . request('about') . '%');
            })
            ->when(request()->filled('country'), function($query) {
                $query->whereHas('country', function ($query) {
                    $query->where('short_code', request('country'));
                });
            })
            ->when(request()->filled('registered_from'), function($query) {
                $query->whereDate('created_at', '>=', request('registered_from'));
            })
            ->when(request()->filled('registered_to'), function($query) {
                $query->whereDate('created_at', '<=', request('registered_to'));
            })
            ->paginate()
            ->withQueryString();

        $countries = Country::get(['name', 'short_code']);

        return view('users.index', compact('users', 'countries'));
    }
}
