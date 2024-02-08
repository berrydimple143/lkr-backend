<?php

namespace App\Http\Controllers\Api;

use App\Models\Area;
use App\Models\User;
use App\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;
use DB;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        try
        {
            $areas = Area::all();
        } catch (Exception $e)
        {
            $areas = $e->getMessage();
        }
        return response()->json([
            'areas' => $areas,
        ]);
    }

    public function create(Request $request)
    {
        try
        {
            $area = Area::create(['name' => $request->name]);            
        } catch (Exception $e)
        {
            $area = $e->getMessage();
        }
        return response()->json([
            'area' => $area,
        ]);
    }

    public function getCollectablesByArea(Request $request)
    {
        $area = "";
        try
        {
            DB::beginTransaction();                
                $users = DB::table('users')
                    ->leftJoin('contacts', 'contacts.user_id', '=', 'users.id')                 
                    ->leftJoin('areas', 'contacts.area_id', '=', 'areas.id')    
                    ->select('users.id AS id', 'areas.name AS area')                         
                    ->where('areas.id', $request->id)
                    ->where('users.role', 'client')    
                    ->get();   
                $allIds = [];                
                foreach($users as $user) {
                    $allIds[] = $user->id;
                    $area = $user->area;
                }
                
                $clients = User::with(['payments', 'contact'])
                    ->select('id', 'last_name', 'first_name', 'middle_name', 'extension_name')
                    ->whereIn('id', $allIds)->orderBy('last_name')->orderBy('first_name')->get();                
            DB::commit();
        } catch (Exception $e)
        {
            DB::rollBack();
            $clients = $e->getMessage();
        }
        return response()->json([
            'clients' => $clients,
            'area' => $area
        ]);
    }

    public function edit(Request $request)
    {
        try
        {
            $area = Area::where('id', $request->rid)->first();         
        } catch (Exception $e)
        {
            $area = $e->getMessage();
        }
        return response()->json([
            'area' => $area
        ]);
    }

    public function update(Request $request)
    {
        try
        {            
            $area = Area::where('id', $request->id)->update(['name' => $request->name]);
        } catch (Exception $e)
        {
            $area = $e->getMessage();
        }
        return response()->json([
            'area' => $area,
        ]);
    }

    public function destroy(Request $request)
    {
        try
        {            
            $area = Area::where('id', $request->id)->delete();
        } catch (Exception $e)
        {
            $area = $e->getMessage();
        }
        return response()->json([
            'area' => $area,
        ]);
    }
}
