<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @internal
 * @covers \App\Http\Controllers\UserController@index;
 */
class UserSearchQueryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed --class=CountrySeeder');

        User::factory()->createMany([
            [
                'name'       => 'Marilyn Bennett',
                'country_id' => 208,
                'created_at' => '2021-11-11 12:00:00',
            ],
            [
                'name'       => 'Louis Cooper',
                'country_id' => 121,
                'about'      => 'nice',
                'created_at' => '2021-11-12 13:00:00',
            ],
            [
                'name'       => 'Mr. Harry Lopez',
                'country_id' => 13,
                'about'      => 'laravel',
                'created_at' => '2021-11-13 14:00:00',
            ],
            [
                'name'       => 'Louis Gonzales',
                'country_id' => 121,
                'created_at' => '2021-11-14 15:00:00',
            ],
            [
                'name'       => 'Betty Wright',
                'country_id' => 13,
                'about'      => 'test nice about',
                'created_at' => '2021-11-15 16:00:00',
            ],
            [
                'name'       => 'Mr. Nicholas Watson',
                'country_id' => 229,
                'about'      => 'laravel',
                'created_at' => '2021-11-16 17:00:00',
            ],
            [
                'name'       => 'Mr. Steve Young',
                'country_id' => 229,
                'about'      => 'test about laravel',
                'created_at' => '2021-11-17 18:00:00',
            ],
            [
                'name'       => 'Jobs Young',
                'country_id' => 13,
                'created_at' => '2021-11-18 19:00:00',
            ],
        ]);
    }

    public function testfiltersByName()
    {
        $query    = ['filter[name]' => 'Young'];
        $response = $this->getJson(route('users.index', $query));

        $response->assertOk();
        $response->assertViewHas('users', function (LengthAwarePaginator $users) use ($query) {
            $hasSearchString = Str::contains(optional($users->first())->name, $query['filter[name]']);
            $isTotalCorrect = $users->total() === 2;

            return $hasSearchString && $isTotalCorrect;
        });
    }

    public function testFiltersByAbout()
    {
        $query    = ['filter[about]' => 'nice'];
        $response = $this->getJson(route('users.index', $query));

        $response->assertOk();
        $response->assertViewHas('users', function (LengthAwarePaginator $users) use ($query) {
            $hasSearchString = Str::contains(optional($users->first())->about, $query['filter[about]']);
            $isTotalCorrect = $users->total() === 2;

            return $hasSearchString && $isTotalCorrect;
        });
    }

    public function testFiltersByCountry()
    {
        $query    = ['filter[country]' => 'au'];
        $response = $this->getJson(route('users.index', $query));

        $response->assertOk();
        $response->assertViewHas('users', function (LengthAwarePaginator $users) use ($query) {
            $hasSearchString = optional($users->first())->country->short_code === $query['filter[country]'];
            $isTotalCorrect = $users->total() === 3;

            return $hasSearchString && $isTotalCorrect;
        });
    }

    public function testFiltersByRegisteredFrom()
    {
        $query    = ['filter[registered_from]' => '2021-11-15'];
        $response = $this->getJson(route('users.index', $query));

        $response->assertOk();
        $response->assertViewHas('users', function (LengthAwarePaginator $users) {
            return $users->total() === 4;
        });
    }

    public function testFiltersByRegisteredTo()
    {
        $query    = ['filter[registered_to]' => '2021-11-15'];
        $response = $this->getJson(route('users.index', $query));

        $response->assertOk();
        $response->assertViewHas('users', function (LengthAwarePaginator $users) {
            return $users->total() === 5;
        });
    }

    public function testFiltersByRegisteredRange()
    {
        $query = [
            'filter[registered_from]' => '2021-11-12',
            'filter[registered_to]'   => '2021-11-13',
        ];
        $response = $this->getJson(route('users.index', $query));

        $response->assertOk();
        $response->assertViewHas('users', function (LengthAwarePaginator $users) {
            return $users->total() === 2;
        });
    }

    public function testFiltersByNameAndCountry()
    {
        $query = [
            'filter[name]'    => 'Louis',
            'filter[country]' => 'lt',
        ];
        $response = $this->getJson(route('users.index', $query));

        $response->assertOk();
        $response->assertViewHas('users', function (LengthAwarePaginator $users) use ($query) {
            $hasRightName = Str::contains(optional($users->first())->name, $query['filter[name]']);
            $hasRightCountry = optional($users->first())->country->short_code === $query['filter[country]'];
            $isTotalCorrect = $users->total() === 2;

            return $hasRightName && $hasRightCountry && $isTotalCorrect;
        });
    }

    public function testFiltersByAboutAndRegisteredDate()
    {
        $query = [
            'filter[registered_from]' => '2021-11-13',
            'filter[registered_to]'   => '2021-11-16',
            'filter[about]'           => 'nice',
        ];
        $response = $this->getJson(route('users.index', $query));

        $response->assertOk();
        $response->assertViewHas('users', function (LengthAwarePaginator $users) use ($query) {
            $hasSearchString = Str::contains(optional($users->first())->about, $query['filter[about]']);

            return $hasSearchString && $users->total() === 1;
        });
    }

    public function testFiltersByAll()
    {
        $query = [
            'filter[name]'            => 'Mr.',
            'filter[about]'           => 'laravel',
            'filter[country]'         => 'us',
            'filter[registered_from]' => '2021-11-14',
            'filter[registered_to]'   => '2021-11-18',
        ];
        $response = $this->getJson(route('users.index', $query));

        $response->assertOk();
        $response->assertViewHas('users', function (LengthAwarePaginator $users) use ($query) {
            $hasRightName = Str::contains(optional($users->first())->name, $query['filter[name]']);
            $hasRightAboutSection = Str::contains(optional($users->first())->about, $query['filter[about]']);
            $hasRightCountry = optional($users->first())->country->short_code === $query['filter[country]'];
            $isTotalCorrect = $users->total() === 2;

            return $hasRightName && $hasRightAboutSection && $hasRightCountry && $isTotalCorrect;
        });
    }
}