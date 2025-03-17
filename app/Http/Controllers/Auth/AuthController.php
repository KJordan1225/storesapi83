<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $rules = [            
            'email' => [
                'required',
                'email',
                'max:255'
            ],
            'password' => [
                'required',
                'string',  
                Password::min(8)
                ->mixedCase()
                ->letters()
                ->symbols()
                ->numbers()              
            ]
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return response()->json([
                'validation errors' => $validator->errors()
            ]);
        }

        $credentials = $request->only('email', 'password' );

        try {
            if (!$token = auth()->attempt($credentials)) {
                throw new UnauthorizedHttpException( 'Bearer', 'User name or password is not valid');
            }
        } catch (JWTException $e) {
            throw $e;
        }

        return $this->respondWithToken($token);
    }

    public function refresh()
    {
        try {
            if (!$token = auth()->getToken()) {
                throw new NotFoundHttpException('Token does not exist');
            }
            return $this->respondWithToken(auth()->refresh($token));
        } catch (JWTException $e) {
            throw $e;
        }
    }

    public function logout()
    {
        try {
            auth()->logout();
        } catch (JWTException $e) {
            throw $e;
        }

        return response()->json (['message' => 'User logged out successfully' ]);
    }

    private function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    
}
