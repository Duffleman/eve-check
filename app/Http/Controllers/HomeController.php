<?php

namespace App\Http\Controllers;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client_id = env('EVE_CLIENT_ID');
        $callback = env('EVE_CALLBACK');
        $eve_uri = 'https://login.eveonline.com/oauth/authorize';

        $params = [
            'response_type' => 'code',
            'redirect_uri'  => $callback,
            'client_id'     => $client_id,
            'scope'         => 'publicData characterLocationRead'
        ];

        $eve_uri .= '?' . http_build_query($params, null, '&', PHP_QUERY_RFC3986);

        $characters = Auth::user()->characters;

        return view('home', compact('eve_uri', 'characters'));
    }
}
