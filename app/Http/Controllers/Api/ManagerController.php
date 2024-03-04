<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Payment;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Exception;
use DB;
use Carbon\Carbon;
use PDF;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        try
        {            
            $managers = User::where('role', 'manager')->get();
        } catch (Exception $e)
        {
            $managers = $e->getMessage();
        }
        return response()->json([
            'managers' => $managers,
        ]);
    }    
    
    public function getAgents(Request $request)
    {
        try
        {            
            $ids = DB::table('managers_agents')->where('manager_id', $request->id)->get('agent_id');
            $arrIds = [];
            foreach($ids as $id) {
                $arrIds[] = $id->agent_id;
            }
            $agents = DB::table('users')->select('last_name', 'first_name', 'middle_name', 'extension_name', 'id')
                    ->where('role', 'agent')
                    ->whereIn('id', $arrIds)
                    ->orderBy('last_name')
                    ->get();
        } catch (Exception $e)
        {
            $agents = $e->getMessage();
        }
        return response()->json([
            'agents' => $agents,
        ]);
    }   

    public function addAgent(Request $request)
    {
        try
        {
            $data = ['manager_id' => $request->manager_id, 'agent_id' => $request->agent_id];
            $status = DB::table('managers_agents')->insert($data);
        } catch (Exception $e)
        {
            $status = $e->getMessage();
        }
        return response()->json([
            'status' => $status,
        ]);
    }

    public function deleteAgent(Request $request)
    {
        try
        {
            $agent = DB::table('managers_agents')->where('agent_id', $request->id)->delete();
        } catch (Exception $e)
        {
            $agent = $e->getMessage();
        }
        return response()->json([
            'agent' => $agent,
        ]);
    }

    public function create(Request $request)
    {
        try
        {
            $id_number = str_replace(':', '', str_replace(' ', '', str_replace('-', '', Carbon::now()->toDateTimeString())));
            $data = [
                'id_number' => $id_number,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'extension_name' => $request->extension_name,
                'role' => 'manager',
            ];
            $manager = User::create($data);     
            $date_bought = Carbon::parse($request->date_bought)->format('Y-m-d H:i:s');
            $contact = Contact::create([
                'user_id' => $manager->id,                
                'mobile' => $request->mobile,                
                'address' => $request->address,                
            ]);       
        } catch (Exception $e)
        {
            $manager = $e->getMessage();
        }
        return response()->json([
            'manager' => $manager,
        ]);
    }

    public function info(Request $request) 
    {
        try
        {
            $manager = DB::table('users')
                    ->leftJoin('contacts', 'users.id', '=', 'contacts.user_id')
                    ->leftJoin('areas', 'contacts.area_id', '=', 'areas.id')
                    ->leftJoin('payments', 'users.id', '=', 'payments.user_id')
                    ->select('users.id AS id', 'users.id_number AS id_number', 'users.first_name AS first_name', 
                            'users.last_name AS last_name', 'users.middle_name AS middle_name', 
                            'users.extension_name AS extension_name', 'contacts.address AS address', 
                            'contacts.block AS block', 'contacts.lot AS lot', 'contacts.price AS price', 
                            'contacts.measure AS measure', 'contacts.mobile AS mobile', 'contacts.date_bought AS date_bought', 
                            'areas.name AS area', 'payments.method AS method', 'payments.amount AS amount',
                            'payments.date_paid AS date_paid'
                    )->where('users.id', $request->id)->first();
        } catch (Exception $e)
        {
            $manager = $e->getMessage();
        }
        return response()->json([
            'manager' => $manager
        ]);
    }

    public function edit(Request $request)
    {
        try
        {
            $manager = User::with('contact')->where('id', $request->rid)->first();
        } catch (Exception $e)
        {
            $manager = $e->getMessage();
        }
        return response()->json([
            'manager' => $manager
        ]);
    }

    public function update(Request $request)
    {
        try
        {            
            $id = $request->id;           
            $managerData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'extension_name' => $request->extension_name,
            ];
            $contactData = [
                'mobile' => $request->mobile,
                'address' => $request->address,
            ];
            $manager = User::where('id', $id)->update($managerData);
            $contact = Contact::where('user_id', $id)->update($contactData);
        } catch (Exception $e)
        {
            $manager = $e->getMessage();
        }
        return response()->json([
            'manager' => $manager,
        ]);
    }   

    public function destroy(Request $request)
    {
        try
        {
            $id = $request->id;
            $contact = Contact::where('user_id', $id)->delete();
            $manager = User::where('id', $id)->delete();
        } catch (Exception $e)
        {
            $manager = $e->getMessage();
        }
        return response()->json([
            'manager' => $manager,
        ]);
    }
}
