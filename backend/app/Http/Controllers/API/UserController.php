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

        if (!empty($decoded->sub)) {
            if (empty(User::where('cognito_sub', $decoded->sub)->first())) {
                User::create([
                    'email' => $decoded->email,
                    'cognito_sub' => $decoded->sub,
                ]);

                return response()->json(['success' => true], 200);
            } else {
                return response()->json([
                    'message' => 'This User already exists',
                    'success' => false,
                ], 400);
            }
        }

        return response()->json([
            'message' => 'Invalid request',
            'success' => false,
        ], 400);
    }
}
