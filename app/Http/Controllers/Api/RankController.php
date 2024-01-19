<?php

namespace App\Http\Controllers\Api;

use App\Models\Rank;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use DB;

class RankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        try
        {
            $ranks = Rank::all();
        } catch (Exception $e)
        {
            $ranks = $e->getMessage();
        }
        return response()->json([
            'ranks' => $ranks,
        ]);
    }

    public function edit(Request $request)
    {
        try
        {
            $rank = Rank::where('id', $request->rid)->first();
        } catch (Exception $e)
        {
            $rank = $e->getMessage();
        }
        return response()->json([
            'rank' => $rank,
        ]);
    }

    public function update(Request $request)
    {
        try
        {
            $data = [
                'code' => $request->code,
                'name' => $request->name,
                'alias' => $request->alias,
            ];
            $rank = Rank::where('id', $request->id)->update($data);
        } catch (Exception $e)
        {
            $rank = $e->getMessage();
        }
        return response()->json([
            'rank' => $rank,
        ]);
    }

    public function destroy(Request $request)
    {
        try
        {
            $rank = Rank::where('id', $request->id)->delete();
        } catch (Exception $e)
        {
            $rank = $e->getMessage();
        }
        return response()->json([
            'rank' => $rank,
        ]);
    }

    public function create(Request $request)
    {
        try
        {
            $data = [
                'code' => $request->code,
                'name' => $request->name,
                'alias' => $request->alias,
            ];
            $rank = Rank::create($data);
        } catch (Exception $e)
        {
            $rank = $e->getMessage();
        }
        return response()->json([
            'rank' => $rank,
        ]);
    }
}
