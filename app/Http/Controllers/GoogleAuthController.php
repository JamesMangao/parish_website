<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    public function auth()
    {
        $client = new \Google\Client();
        $client->setHttpClient(new \GuzzleHttp\Client());
        $client->setAuthConfig(storage_path('app/google_oauth_client.json'));
        $client->addScope('https://www.googleapis.com/auth/presentations');
        $client->addScope('https://www.googleapis.com/auth/drive');
        $client->setRedirectUri(url('/google/callback'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        $state = bin2hex(random_bytes(16));
        session(['google_oauth_state' => $state]);
        $client->setState($state);

        return redirect($client->createAuthUrl());
    }

    public function callback(Request $request)
    {
        if ($request->get('state') !== session('google_oauth_state')) {
            return 'Invalid state parameter.';
        }

        $client = new \Google\Client();
        $client->setHttpClient(new \GuzzleHttp\Client());
        $client->setAuthConfig(storage_path('app/google_oauth_client.json'));
        $client->setRedirectUri(url('/google/callback'));
        $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

        \App\Models\Setting::updateOrCreate(
            ['key' => 'google_token'],
            ['value' => json_encode($token)]
        );

        return 'Google connected! Token saved. You can close this tab.';
    }
}