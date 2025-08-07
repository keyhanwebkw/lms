<?php

namespace App\Guards;

use App\Enums\UserTypes;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Keyhanweb\Subsystem\Models\PersonalAccessToken;

class ChildrenGuard implements Guard
{
    use GuardHelpers;

    protected Request $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function user()
    {
        if ( !is_null($this->user)) {
            return $this->user;
        }

        $token = $this->request->bearerToken();
        if ( !$token) {
            return null;
        }

        $accessToken = PersonalAccessToken::findToken($token);
        if ( !$accessToken) {
            return null;
        }

        $user = $accessToken->tokenable;
        if ( !$user || $user->type != UserTypes::Child->value) {
            return null;
        }

        return $this->user = $user;
    }

    public function validate(array $credentials = []): false
    {
        return false;
    }
}
