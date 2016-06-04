<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Auth;

class UsersController extends Controller
{
    public function update(Requests\SetMobileRequest $request)
    {
        $phone = $request->get('phone');
        $frequency = $request->get('frequency');
        $user = Auth::user();

        $user->mobile_phone = $phone;
        $user->frequency = $frequency;
        $user->save();
    }
}
