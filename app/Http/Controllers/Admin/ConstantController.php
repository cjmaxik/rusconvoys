<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\DLC;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

/**
 * Class ConstantController
 *
 * @package App\Http\Controllers\Admin
 */
class ConstantController extends Controller
{
    use SEOToolsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->seo()->setTitle('Константы');

        $countries = Country::with('cities')->get()->sortBy(function ($country) {
            return $country->cities->count();
        });

        $dlcs = DLC::get();

        $background = 'white';

        return view('admin.constant', compact('countries', 'dlcs', 'background'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function store_city(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required|exists:countries,id',
            'name'       => 'required|unique:cities,name',
            'rus_name'   => 'required|unique:cities,rus_name',
            'dlc_id'     => 'required|exists:dlcs,id',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        };

        City::insert([
            'country_id' => $request->country_id,
            'name'       => htmlentities($request->name),
            'rus_name'   => htmlentities($request->rus_name),
            'dlc_id'     => $request->dlc_id,
        ]);

        Cache::flush('countries');

        return 'OK';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store_country(Request $request)
    {
        //
    }
}
