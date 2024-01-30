<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'registerAdmin']]);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|between:2,100',
            'name' => 'required|string|between:2,100',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->password)
            ]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function registerAdmin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|between:2,100',
            'name' => 'required|string|between:2,100',
            'password' => 'required|string|confirmed|min:6',
            'secret' => ['required', 'string', 'in:notsecureiknow'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->password),
                'is_admin' => true
            ]
        ));

        return response()->json([
            'message' => 'Admin successfully registered',
            'user' => $user
        ], 201);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(
            [
                'message' => 'User successfully signed out'
            ]
        );
    }

    public function refresh(): JsonResponse
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function myProfile(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    private function createNewToken(string $token): JsonResponse
    {
        return response()->json(
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => auth()->user() // not safe
            ]
        );
    }

    protected function registered(Request $request, $user)
    {
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }
}
