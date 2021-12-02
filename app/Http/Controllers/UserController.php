<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $query = User::query();

        if (request()->filled('name')) {
            $query->where('name', 'LIKE', '%' . request('name') . '%');
        }

        if (request()->filled('about')) {
            $query->where('about', 'LIKE', '%' . request('about') . '%');
        }

        if (request()->filled('country')) {
            $query->whereHas('country', function ($query) {
                $query->where('short_code', request('country'));
            });
        }

        if (request()->filled('registered_from')) {
            $query->whereDate('created_at', '>=', request('registered_from'));
        }

        if (request()->filled('registered_to')) {
            $query->whereDate('created_at', '<=', request('registered_to'));
        }

        $users = $query->with('country')->paginate()->withQueryString();

        $countries = Country::get(['name', 'short_code']);

        return view('users.index', compact('users', 'countries'));
    }
}
