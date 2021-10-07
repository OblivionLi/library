<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleCreateRequest;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Http\Resources\Role\RoleIndexResource;
use App\Http\Resources\Role\RoleShowResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return collection of all roles
        return RoleIndexResource::collection(Role::info()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRequest $request)
    {
        // create new role
        Role::create([
            'name'      => $request->name,
            'is_admin'  => 0
        ]);

        // return success message
        $response = ['message' => "Role create success"];
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // find role by id
        $role = Role::info()->find($id);

        // if role doesnt exist return error message
        if (!$role) return response()->json(['message' => 'Role does not exist']);

        // return data
        return new RoleShowResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, $id)
    {
        // find role by id
        $role = Role::info()->find($id);

        // if role doesnt exist return error message
        if (!$role) return response()->json(['message' => 'Role does not exist']);

        // update role with request data
        $role->name     = $request->name;
        $role->is_admin = $request->is_admin;

        // save new role data
        $role->save();

        // sync role's permissions relationship data
        $role->permissions()->sync($request->permissions);

        // return success message
        $response = ['message' => "Role update success"];
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
        // find role by id
        $role = Role::info()->find($id);

        // if role doesnt exist return error message
        if (!$role) return response()->json(['message' => 'Role does not exist']);
        
        // if role name == Guest or Admin return error message
        // because there is validation based on these 2 names (Guest/Admin) in the front end that differentiate between 
        // users access views
        if ($role->name == 'Guest' || $role->name == 'Admin') return response()->json(['message' => "Sorry you can't delete the Guest/Admin role"]);

        // detach relationship from this role
        $role->permissions()->detach();
        $role->users()->detach();

        // delete role
        $role->delete();

        // return success message
        $response = ['message' => "Role delete success"];
        return response()->json($response);
    }
}
