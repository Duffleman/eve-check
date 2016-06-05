<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Auth;

class UsersController extends Controller
{
    public function update(Requests\UpdateNotificationSettings $request)
    {
        $frequency = $request->get('frequency');
        $user = Auth::user();

        $user->frequency = $frequency;
        $user->save();
    }
}
