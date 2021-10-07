<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserIndexResource;
use App\Http\Resources\User\UserShowResource;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return collection of all users
        return UserIndexResource::collection(User::info()->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // find user by id
        $user = User::info()->find($id);

        // if user doesnt exist return error message
        if (!$user) return response()->json(['message' => 'User does not exist']);

        // return data
        return new UserShowResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // find user by id
        $user = User::info()->find($id);

        // if user doesnt exist return error message
        if (!$user) return response()->json(['message' => 'User does not exist']);

        // validate request
        $request->validate([
            'name'      => 'string|required',
            'email'     => [
                Rule::unique('users')->ignore($user->id),
                'required'
            ],
            'password'  => 'confirmed'
        ]);

        // update user with request data
        $user->name     = $request->name;
        $user->email    = $request->email;

        // save new user data
        $user->save();

        // sync user's roles relationship data
        $user->roles()->sync($request->roles);

        // return success message
        $response = ['message' => "User update success"];
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // find user by id
        $user = User::info()->find($id);

        // if user doesnt exist return error message
        if (!$user) return response()->json(['message' => 'User does not exist']);

        // detach relationship from this user
        $user->roles()->detach();

        // delete user
        $user->delete();

        // return success message
        $response = ['message' => "User delete success"];
        return response()->json($response);
    }
}
