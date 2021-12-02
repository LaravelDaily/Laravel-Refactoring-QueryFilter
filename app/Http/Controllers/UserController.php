<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index()
    {
        $users = QueryBuilder::for(User::class)
            ->with('country')
            ->allowedFilters([
                'name',
                'about',
                AllowedFilter::scope('country'),
                AllowedFilter::scope('registered_from'),
                AllowedFilter::scope('registered_to')
            ])
            ->paginate()
            ->withQueryString();

        $countries = Country::get(['name', 'short_code']);

        return view('users.index', compact('users', 'countries'));
    }
}
