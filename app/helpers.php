<?php

if (!function_exists('client_auth')) {
    function client_auth()
    {
        $client_id = env('EVE_CLIENT_ID');
        $client_secret = env('EVE_CLIENT_SECRET');

        $header = $client_id . ':' . $client_secret;

        return base64_encode($header);
    }
}
