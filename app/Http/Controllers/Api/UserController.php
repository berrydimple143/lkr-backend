<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Exception;
use DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        try
        {
            $users = User::all();
        } catch (Exception $e)
        {
            $users = $e->getMessage();
        }
        return response()->json([
            'users' => $users,
        ]);
    }

    public function create(Request $request)
    {
        try
        {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ];
            $user = User::create($data);
            $role = Role::where('id', $request->role_id)->first();
            $user->assignRole($role);
        } catch (Exception $e)
        {
            $user = $e->getMessage();
        }
        return response()->json([
            'user' => $user,
        ]);
    }

    public function edit(Request $request)
    {
        try
        {
            $user = User::where('id', $request->rid)->first();
            $role_id = $user->roles->pluck('id')[0];
        } catch (Exception $e)
        {
            $user = $e->getMessage();
        }
        return response()->json([
            'user' => $user,
            'role_id' => $role_id,
        ]);
    }

    public function update(Request $request)
    {
        try
        {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
            ];
            $id = $request->id;
            $user = User::where('id', $id)->first();
            $role_id = $user->roles->pluck('id')[0];
            $removeRole = DB::table('model_has_roles')
                ->where('model_id', $id)
                ->where('role_id', $role_id)
                ->delete();
            $role = Role::where('id', $request->role_id)->first();
            $user->assignRole($role);
            $user = User::where('id', $id)->update($data);
        } catch (Exception $e)
        {
            $user = $e->getMessage();
        }
        return response()->json([
            'user' => $user,
        ]);
    }

    public function destroy(Request $request)
    {
        try
        {
            $id = $request->id;
            $user = User::where('id', $id)->first();
            $role_id = $user->roles->pluck('id')[0];
            $removeRole = DB::table('model_has_roles')
                ->where('model_id', $id)
                ->where('role_id', $role_id)
                ->delete();
            $user = User::where('id', $id)->delete();
        } catch (Exception $e)
        {
            $user = $e->getMessage();
        }
        return response()->json([
            'user' => $user,
        ]);
    }
}
