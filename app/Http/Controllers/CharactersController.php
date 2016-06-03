<?php

namespace App\Http\Controllers;

use App\Character;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CharactersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Auth::user()->characters()->get()->map(function ($item) {
            $item->checked = false;

            if ($item->monitored == 'yes') {
                $item->checked = true;
            }

            $item->human_date = $item->created_at->diffForHumans();

            return $item;
        });
    }

    public function update(Request $request, Character $character)
    {
        if ($character->user_id != Auth::user()->id) {
            throw new AuthorizationException('character_does_not_belong');
        }

        if ($request->has('character.checked')) {
            $checked = $request->get('character')['checked'];
            $character->monitored = 'yes';
            if ($checked === true) {
                $character->monitored = 'no';
            }
            $character->save();
        }

        return ['success' => 'true'];
    }

    public function delete(Character $character)
    {
        if ($character->user_id != Auth::user()->id) {
            throw new AuthorizationException('character_does_not_belong');
        }

        $character->delete();
    }
}
