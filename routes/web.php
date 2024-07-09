<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// protecting routes with sanctum
// Ensure you have the HasApiTokens trait included in User model. otherwise errors

// Setup route to create tokens

Route::get('/setup',function(){
    $credentials = [
        'email' => 'admin@admin.com',
        'password' => 'password',
    ];
    
    if (!Auth::attempt($credentials)) {

          // Create a new user with the provided credentials
        $user = new \App\Models\User();

        $user->name = 'Admin';
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->save();
    }

    if(Auth::attempt($credentials)){
        $user = Auth::user();

        // Create an API token for the authenticated user - 3 types
        // Token Name , Token Abilities
        // you want to create separete middleware and use that middleware in api routes for check ability real[authorization]. this is only representation.
        // $user->createToken('admin-token', ['create', 'update', 'delete']);
        // this will also work but it's check only authenticate part only. not authorization. just protecting routes with sanctum.
        // When a request is made to a protected route, the client must include the token in the Authorization header as a Bearer token: Authorization: Bearer {token}.

        $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
        $updateToken = $user->createToken('update-token', ['create', 'update']);
        $basicToken = $user->createToken('basic-token');

        return [
            'admin' => $adminToken->plainTextToken,
            'update' => $updateToken->plainTextToken,
            'basic' => $basicToken->plainTextToken,
        ];
    }
});
