<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post('http://api.codersfree.test/v1/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->status() == 404) {
            return back()->withErrors('These credentials do not match our records.');
        }

        $service = $response->json();

        $user = User::updateOrCreate(['email' => $request->email], $response['data']);

        if(!$user->accessToken){
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->post('http://api.codersfree.test/oauth/token', [
                'grant_type'    => 'password',
                'client_id'     => config('services.cordersfree.client_id'),
                'client_secret' => config('services.cordersfree.client_secret'),
                'username'      => $request->email,
                'password'      => $request->password,
            ]);

            $access_token = $response->json();
            $user->accessToken()->create([
                'service_id' => $service['data']['id'],
                'access_token' => $access_token['access_token'],
                'refresh_token' => $access_token['refresh_token'],
                'expires_at' => now()->addSecond($access_token['expires_in']),
            ]);
            return $access_token;
        }
        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
