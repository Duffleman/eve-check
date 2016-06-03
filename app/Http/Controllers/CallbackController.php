<?php

namespace App\Http\Controllers;

use App\Character;
use App\Http\Requests\SSOCallbackRequest;
use Auth;
use Duffleman\JSONClient\JSONClient;

class CallbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function handle(SSOCallbackRequest $request)
    {
        $code = $request->get('code');
        $client = new JSONClient('https://login.eveonline.com/', [
            'Authorization' => client_auth(),
        ]);

        $token = $client->post('oauth/token', [
            'grant_type' => 'authorization_code',
            'code'       => $code,
        ]);

        $token_string = "{$token->token_type} {$token->access_token}";

        $auth_client = new JSONClient('https://login.eveonline.com/', [
            'Authorization' => $token_string,
        ]);

        $character = $auth_client->get('oauth/verify');

        $toon = Character::firstOrNew([
            'id' => $character->CharacterID,
        ]);

        $toon->name = $character->CharacterName;
        $toon->owner = $character->CharacterOwnerHash;
        $toon->refresh_token = $token->refresh_token;

        Auth::user()->characters()->save($toon);

        return redirect('/');
    }
}
