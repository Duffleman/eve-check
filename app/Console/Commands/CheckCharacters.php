<?php

namespace App\Console\Commands;

use App\Character;
use Duffleman\JSONClient\JSONClient;
use Illuminate\Console\Command;
use Mail;

class CheckCharacters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the characters online status.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $characters = Character::where('monitored', 'yes')->get();
        foreach ($characters as $character) {
            \Log::info("Checking: {$character->name}");
            $cache_key = "at_{$character->id}";

            if (\Cache::has($cache_key)) {
                \Log::info("{$character->name}: Access token loaded from cache.");
                $access_token = \Cache::get($cache_key);
            } else {
                \Log::info("{$character->name}: Access token generated from refresh token.");
                $refresh_token = $character->refresh_token;
                $client = new JSONClient('https://login.eveonline.com/', [
                    'Authorization' => client_auth(),
                ]);
                $token = $client->post('oauth/token', [
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => $refresh_token,
                ]);

                $access_token = $token->access_token;
                $expires_in = $token->expires_in;

                \Cache::put($cache_key, $access_token, $expires_in);
            }

            $client = new JSONClient('https://crest-tq.eveonline.com/', [
                'Authorization' => "Bearer {$access_token}",
            ]);
            try {
                $location = $client->mode(1)->get("/characters/{$character->id}/location/");
            } catch (\Exception $ex) {
                \Cache::forget($cache_key);

                return;
            }

            if (empty($location)) {
                \Log::info("{$character->name}: Character is offline!");

                $user = $character->user;
                Mail::raw("{$character->name} is offline!", function ($message) use ($user) {
                    $message->to($user->mobile_phone);
                });
            } else {
                \Log::info("{$character->name}: Character is online in {$location['solarSystem']['name'] } :)");
            }
        }
    }
}
