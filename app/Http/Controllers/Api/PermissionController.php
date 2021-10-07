<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\PermissionStoreRequest;
use App\Http\Requests\Permission\PermissionUpdateRequest;
use App\Http\Resources\Permission\PermissionIndexResource;
use App\Http\Resources\Permission\PermissionShowResource;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return collection of all permissions
        return PermissionIndexResource::collection(Permission::info()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionStoreRequest $request)
    {
        // create new permission
        Permission::create([
            'name'      => $request->name,
        ]);

        // return success message
        $response = ['message' => "Permission create success"];
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
        // find permission by id
        $permission = Permission::info()->find($id);

        // if permission doesnt exist return error message
        if (!$permission) return response()->json(['message' => 'Permission does not exist']);

        // return data
        return new PermissionShowResource($permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionUpdateRequest $request, $id)
    {
        // find permission by id
        $permission = Permission::info()->find($id);

        // if permission doesnt exist return error message
        if (!$permission) return response()->json(['message' => 'Permission does not exist']);

        // update permission with request data
        $permission->name = $request->name;

        // save new permission data
        $permission->save();

        // return success message
        $response = ['message' => "Permission update success"];
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
        // find permission by id
        $permission = Permission::info()->find($id);

        // if permission doesnt exist return error message
        if (!$permission) return response()->json(['message' => 'Permission does not exist']);
        
        // detach relationship from this permission
        $permission->roles()->detach();

        // delete permission
        $permission->delete();

        // return success message
        $response = ['message' => "Permission delete success"];
        return response()->json($response);
    }
}
