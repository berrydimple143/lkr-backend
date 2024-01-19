<?php

namespace App\Http\Controllers\Api;

use App\Models\Area;
use App\Http\Controllers\Controller;
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
