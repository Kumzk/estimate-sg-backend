<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Cognito\JWTVerifier;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var JWTVerifier
     */
    private $JWTVerifier;

    /**
     * CognitoGuard constructor.
     */
    public function __construct(JWTVerifier $JWTVerifier)
    {
        $this->JWTVerifier = $JWTVerifier;
    }

    public function store(Request $request)
    {
        $jwt = $request->bearerToken();
        if (!$jwt) {
            return null;
        }

        $decoded = $this->JWTVerifier->decode($jwt);

        if ($decoded) {
            $user = new User();
            $user->cognito_sub = $decoded->sub;
            if ($user->save()) {
                return $decoded->sub;
            }
        }

        return $decoded;
    }
}
