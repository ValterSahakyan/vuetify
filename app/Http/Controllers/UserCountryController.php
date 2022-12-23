<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\UserCountry;
use Illuminate\Http\Request;

class UserCountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserCountry  $userCountry
     * @return \Illuminate\Http\Response
     */
    public function show(UserCountry $userCountry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserCountry  $userCountry
     * @return \Illuminate\Http\Response
     */
    public function edit(UserCountry $userCountry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserCountry  $userCountry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserCountry $userCountry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserCountry  $userCountry
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserCountry $userCountry)
    {
        //
    }

    public function getCountries() {
        $countries = Country::orderBy('name')->get();
        return response() -> json([
            'success' => 'success',
            'countries' => $countries
        ]);
    }
}
