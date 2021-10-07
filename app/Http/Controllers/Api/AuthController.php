<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login\LoginRequest;
use App\Http\Requests\Auth\Register\RegisterRequest;
use App\Http\Resources\Auth\Login\LoginResource;
use App\Http\Resources\Auth\Register\RegisterResource;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // register user 
    public function register(RegisterRequest $request)
    {
        // get role where name is Guest
        $role = Role::where('name', 'Guest')->first();

        // check if role 'Guest' doesnt exist 
        if (!$role) {
            // create the role 'Guest'
            $role = Role::create([
                'name'      => 'Guest',
                'is_admin'  => false,
            ]);
        }

        // create the user  
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        // attach user's role id in relation 
        $user->roles()->attach($role->id);

        // create token
        $tokenResult    = $user->createToken('Personal Access Token');
        $token          = $tokenResult->token;

        // check if request remember_me value is true(1)
        if ($request->remember_me) {
            // add 1 more week to the token expiration date
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        // save token
        $token->save();

        // return resource with token
        return new RegisterResource($user, $tokenResult->accessToken);
    }

    // login user
    public function login(LoginRequest $request)
    {
        // find user by email
        $user = User::where('email', $request->email)->first();

        // check if $user exist 
        if ($user) {
            // check if password match
            if (Hash::check($request->password, $user->password)) {
                // create new token
                $tokenResult = $user->createToken('Personal Access Token');

                // get new token and store it in token
                $token = $tokenResult->token;

                // check if request remember_me value is true(1)
                if ($request->remember_me) {
                    // add 1 more week to the token expiration date
                    $token->expires_at = Carbon::now()->addWeeks(1);
                }

                // save token
                $token->save();

                // return resource with token
                return new LoginResource($user, $tokenResult->accessToken);
            }
        }

        // return error if user doesnt exist 
        $response = ['message' => 'Invalid user credentials..'];
        return response()->json($response);
    }
}
