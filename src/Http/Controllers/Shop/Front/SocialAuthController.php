<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAuthController extends Controller
{
    public function __construct()
    {
        return $this->middleware('guest');
    }

    /**
       * Create a redirect method to facebook api.
       *
       * @return void
    */
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
        * Return a callback method from facebook api.
        *
        * @return callback URL from facebook
    */
    public function callback($provider)
    {
<<<<<<< HEAD
        // dd(Socialite::driver($provider)->user());
=======
    	// dd(Socialite::driver($provider)->user());
>>>>>>> b65fe74e850fc7629857fe2a84644ada9a7aef8b
        $user = $this->createOrGetUser(Socialite::driver($provider)->user(), $provider);
        auth()->login($user);
        return redirect()->to('/');
    }

    private function createOrGetUser(ProviderUser $providerUser, $provider)
    {
        $account = SocialFacebookAccount::whereProvider($provider)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        dd($account);
        if ($account) {
            return $account->user;
        } else {
            // $account = new SocialFacebookAccount([
            //     'provider_user_id' => $providerUser->getId(),
            //     'provider' => 'facebook'
            // ]);

            // $user = User::whereEmail($providerUser->getEmail())->first();

            // if (!$user) {

            //     $user = User::create([
            //         'email' => $providerUser->getEmail(),
            //         'name' => $providerUser->getName(),
            //         'password' => md5(rand(1,10000)),
            //     ]);
            // }

            // $account->user()->associate($user);
            // $account->save();

            // return $user;
        }
    }
}
