<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPassword\ForgotPasswordRequest;
use App\Http\Requests\Auth\Login\LoginRequest;
use App\Http\Requests\Auth\Register\RegisterRequest;
use App\Http\Requests\Auth\ResetPassword\ResetPasswordRequest;
use App\Http\Requests\Auth\UpdateUser\UpdateUserRequest;
use App\Http\Resources\Auth\Login\LoginResource;
use App\Http\Resources\Auth\Register\RegisterResource;
use App\Mail\ForgotPassword;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    // logout user
    public function logout(Request $request) {
        // revoke user token
        $request->user()->token()->revoke();

        // return success message
        $response = ['message' => "You have been logged out"];
        return response()->json($response);
    }

    // forgot-password
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // create a token with 60 random characters
        $token = Str::random(60);

        // insert data into password_resets table
        // the table takes an email, a token and a date when the record was inserted
        DB::table('password_resets')->insert([
            [
                'email'         => $request->email,
                'token'         => $token,
                'created_at'    => Carbon::now()
            ]
        ]);

        // get user where the email is = with the email from request
        $user = User::where('email', $request->email)->first();

        // prepare an array with the user found and the randomly generated token
        // the data will be used in the sent email
        $data = [
            'user'  => $user,
            'token' => $token
        ];

        // sent the prepared data to the email inside the request
        Mail::to($request->email)->send(new ForgotPassword($data));

        // return success message
        $response = ['message' => 'Email sent with success'];
        return response()->json($response, 200);
    }

    // reset password
    public function resetPassword(ResetPasswordRequest $request, $email)
    {
        // get user by email
        $user = User::where('email', $email)->first();

        // check if user exist
        if ($user) {
            // if password exist in request then hash it and add it to $user obj
            $request->password && $user->password = Hash::make($request->password);

            // save user to db with the newly hashed password
            $user->save();

            // delete token from password_resets table
            DB::table('password_resets')->where('email', $email)->delete();
        } else {
            // if user doesnt exist then throw error response
            $response = ['message' => 'User does not exist..'];
            return response()->json($response, 404);
        }

        // return success message
        $response = ['message' => 'User password changed with success'];
        return response()->json($user, 200);
    }

    // get token from password_resets table
    public function getToken($token)
    {
        // return a json response with the token
        return response()->json(DB::select('select *  from password_resets where token = :token', ['token' => $token]));
    }

    // update user credentials
    public function update(UpdateUserRequest $request, $id)
    {
        // get user by id
        $user = User::find($id);

        // if user exist
        if ($user) {
            // insert data into user object
            $user->name = $request->name;
            $user->email = $request->email;

            // check if request has password and hash it
            $request->password && Hash::make($request->password);

            // save user obj with the new data
            $user->save();
        } else {
            // return error message
            $response = ['message' => 'User does not exist..'];
            return response()->json($response, 404);
        }

        // return success message
        $response = ['message' => 'User Credentials have been updated successfully'];
        return response()->json($user, 200);
    }
}
