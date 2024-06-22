<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\EmailOrPhoneRequired;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
{
    
$validator = Validator::make($request->all(), [
    'name' => 'required|string|max:255',
    'email' => [
        'nullable',
        'string',
        'email',
        'max:255',
        'unique:users',
        new EmailOrPhoneRequired()
    ],
    'phone' => [
        'nullable',
        'string',
        'max:255',
        'unique:users',
        new EmailOrPhoneRequired()
    ],
    'password' => 'required|string|confirmed',
    'age' => 'nullable|integer',
    // 'gender' => 'required|string'
]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    if(empty($request->email) && empty($request->phone)){
        return 'Телефон либо электронная почта должна быть заполнена';

    }
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'age' => $request->age,
        'gender' => $request->gender ?  $request->gender : "male"
    ]);
    $token = $user->createToken('Personal Access Token')->plainTextToken;

    return response()->json([
        'message' => 'User successfully registered',
        'user' => $user,
        'token' => $token

    ], 201);
}


    // Login user
    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);
    
        // Determine if the login is an email or a phone number
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
    
        // Attempt to authenticate with the determined field
        if (!Auth::attempt([$field => $request->login, 'password' => $request->password])) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        // Retrieve user after successful authentication
        $user = Auth::user();
        $token = $user->createToken('Personal Access Token')->plainTextToken;
    
        return response()->json([
            'message' => 'User logged in successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    
}
