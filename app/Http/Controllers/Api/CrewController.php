<?php

namespace App\Http\Controllers\Api;

use App\Models\Crew;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use DB;

class CrewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function count(Request $request)
    {
        try
        {
            $crew_count = Crew::all()->count();
        } catch (Exception $e)
        {
            $crew_count = $e->getMessage();
        }
        return response()->json([
            'crew_count' => $crew_count,
        ]);
    }

    public function index(Request $request)
    {
        try
        {
            $crews = Crew::with(['rank'])->get();
        } catch (Exception $e)
        {
            $crews = $e->getMessage();
        }
        return response()->json([
            'crews' => $crews,
        ]);
    }

    public function byRank(Request $request)
    {
        try
        {
            $crews = Crew::with(['rank'])->where('rank_id', $request->rid)->get();
        } catch (Exception $e)
        {
            $crews = $e->getMessage();
        }
        return response()->json([
            'crews' => $crews,
        ]);
    }

    public function create(Request $request)
    {
        try
        {
            $data = [
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'email' => $request->email,
                'birth_date' => $request->birth_date,
                'age' => $request->age,
                'height' => $request->height,
                'weight' => $request->weight,
                'rank_id' => $request->rank_id,
                'user_id' => $request->user_id,
            ];
            $crew = Crew::create($data);
        } catch (Exception $e)
        {
            $crew = $e->getMessage();
        }
        return response()->json([
            'crew' => $crew,
        ]);
    }

    public function edit(Request $request)
    {
        try
        {
            $crew = Crew::with(['rank'])->where('id', $request->rid)->first();
        } catch (Exception $e)
        {
            $crew = $e->getMessage();
        }
        return response()->json([
            'crew' => $crew,
        ]);
    }

    public function update(Request $request)
    {
        try
        {
            $data = [
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'email' => $request->email,
                'birth_date' => $request->birth_date,
                'age' => $request->age,
                'height' => $request->height,
                'weight' => $request->weight,
                'rank_id' => $request->rank_id,
                'user_id' => $request->user_id,
            ];
            $crew = Crew::where('id', $request->id)->update($data);
        } catch (Exception $e)
        {
            $crew = $e->getMessage();
        }
        return response()->json([
            'crew' => $crew,
        ]);
    }

    public function destroy(Request $request)
    {
        try
        {
            $crew = Crew::where('id', $request->id)->delete();
        } catch (Exception $e)
        {
            $crew = $e->getMessage();
        }
        return response()->json([
            'crew' => $crew,
        ]);
    }
}
