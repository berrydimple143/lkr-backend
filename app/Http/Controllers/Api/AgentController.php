<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Payment;
use App\Models\Contact;
use App\Models\Expense;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Exception;
use DB;
use Carbon\Carbon;
use PDF;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function printSOA(Request $request) {
        $data = [
            'title' => 'Statement of Account',
            'pdate' => date('m/d/Y'),
        ]; 
        $pdf = Pdf::loadView('pdf.soa', $data);
        $pdf->stream();        
        //return $pdf->download('soa.pdf');
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        return response()->download($pdf, 'filename.pdf', $headers);
    }

    public function index(Request $request)
    {
        try
        {
            //$query = DB::raw("(CASE WHEN payments.amount != NULL THEN SUM(payments.amount) ELSE 'none' END) AS amount");
            $agents = User::with('payments', 'contact')->where('role', 'agent')->get();
            // $clients = DB::table('users')
            //         ->leftJoin('contacts', 'users.id', '=', 'contacts.user_id')
            //         ->leftJoin('areas', 'contacts.area_id', '=', 'areas.id')
            //         ->leftJoin('payments', 'users.id', '=', 'payments.user_id')
            //         ->select('users.id AS id', 'users.id_number AS id_number', 'users.first_name AS first_name', 
            //                 'users.last_name AS last_name', 'users.middle_name AS middle_name', 
            //                 'users.extension_name AS extension_name', 'contacts.address AS address', 
            //                 'contacts.block AS block', 'contacts.lot AS lot', 'contacts.price AS price', 
            //                 'contacts.measure AS measure', 'contacts.mobile AS mobile', 'contacts.date_bought AS date_bought', 
            //                 'areas.name AS area', 'payments.amount AS amount'               
            //         )->where('role', 'client')->get();

            // $clients = User::with(['payments', 'contacts' => function (Builder $query) {
            //     //$query->where('role', 'client');
            // }])->where('role', 'client')->get();

        } catch (Exception $e)
        {
            $agents = $e->getMessage();
        }
        return response()->json([
            'agents' => $agents,
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
                'role' => 'agent',
            ];
            $agent = User::create($data);     
            $date_bought = null;
            if($request->date_bought) {
                $date_bought = Carbon::parse($request->date_bought)->format('Y-m-d H:i:s');
            }            
            $contact = Contact::create([
                'user_id' => $agent->id,  
                'mobile' => $request->mobile,                
                'address' => $request->address,
                'price' => $request->price,
                'date_bought' => $date_bought,
            ]);       
        } catch (Exception $e)
        {
            $agent = $e->getMessage();
        }
        return response()->json([
            'agent' => $agent,
        ]);
    }

    public function updatePayment(Request $request) 
    {
        try
        {
            $clientIds = User::where('role', 'agent')->get('id');
            $status = Payment::whereIn('user_id', $clientIds)->update(['type' => 'agent']);
        } catch (Exception $e)
        {
            $status = $e->getMessage();
        }
        return response()->json([
            'status' => $status,
        ]);
    }

    public function payment(Request $request)
    {
        try
        {
            $contact = Contact::where('user_id', $request->id)->first();
            $totalPayments = Payment::where('user_id', $request->id)->sum('amount');
            $tp = (float)$totalPayments;
            $cp = (float)$contact->price;
            if($tp > $cp) {
                $payment = 'paid';
            } else if(($tp + (float)$request->amount) > $cp) {
                $payment = 'exceeded';
            } else {
                $date_paid = Carbon::parse($request->date_paid)->format('Y-m-d H:i:s');   
                $data = [
                    'user_id' => $request->id,
                    'date_paid' => $date_paid,
                    'amount' => $request->amount,
                    'method' => $request->method,
                    'received_by' => $request->received_by,
                    'type' => 'agent',
                ];
                $user = User::find($request->id);
                $desc = $request->received_by. " COMM - " .$user->last_name;
                $payment = Payment::create($data); 
                $transData = [
                    'amount' => $request->amount,
                    'type' => 'commission',
                    'transaction_date' => $date_paid,
                    'description' => $desc,
                ];
                $transaction = Expense::create($transData);
            }                                      
        } catch (Exception $e)
        {
            $payment = $e->getMessage();
        }
        return response()->json([
            'payment' => $payment,
        ]);
    }

    public function payments(Request $request) 
    {
        try
        {
            $payments = Payment::where('user_id', $request->id)->orderBy('date_paid')->get();
            $totalPayments = Payment::where('user_id', $request->id)->sum('amount');
        } catch (Exception $e)
        {
            $payments = $e->getMessage();
        }
        return response()->json([
            'payments' => $payments,
            'totalPayments' => $totalPayments
        ]);
    }

    public function info(Request $request) 
    {
        try
        {
            $agent = DB::table('users')
                    ->leftJoin('contacts', 'users.id', '=', 'contacts.user_id')
                    ->leftJoin('payments', 'users.id', '=', 'payments.user_id')
                    ->select('users.id AS id', 'users.id_number AS id_number', 'users.first_name AS first_name', 
                            'users.last_name AS last_name', 'users.middle_name AS middle_name', 
                            'users.extension_name AS extension_name', 'contacts.address AS address',                             
                            'contacts.mobile AS mobile', 'contacts.date_bought AS date_bought', 'contacts.price AS price', 
                            'payments.method AS method', 'payments.amount AS amount',
                            'payments.date_paid AS date_paid', 'payments.received_by AS received_by'
                    )->where('users.id', $request->id)->first();
        } catch (Exception $e)
        {
            $agent = $e->getMessage();
        }
        return response()->json([
            'agent' => $agent
        ]);
    }

    public function edit(Request $request)
    {
        try
        {
            $agent = User::with('contact')->where('id', $request->rid)->first();
        } catch (Exception $e)
        {
            $agent = $e->getMessage();
        }
        return response()->json([
            'agent' => $agent
        ]);
    }

    public function update(Request $request)
    {
        try
        {            
            $id = $request->id;           
            $agentData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'extension_name' => $request->extension_name,
            ];
            $date_bought = Carbon::parse($request->date_bought)->format('Y-m-d H:i:s');
            $contactData = [                
                'mobile' => $request->mobile,                
                'price' => $request->price,
                'address' => $request->address,
                'date_bought' => $date_bought,                
            ];
            $agent = User::where('id', $id)->update($agentData);
            $contact = Contact::where('user_id', $id)->update($contactData);
        } catch (Exception $e)
        {
            $agent = $e->getMessage();
        }
        return response()->json([
            'agent' => $agent,
        ]);
    }

    public function destroyPayment(Request $request)
    {
        try
        {           
            $payment = Payment::where('id', $request->id)->delete();            
        } catch (Exception $e)
        {
            $payment = $e->getMessage();
        }
        return response()->json([
            'payment' => $payment,
        ]);
    }

    public function destroy(Request $request)
    {
        try
        {
            $id = $request->id;
            $contact = Contact::where('user_id', $id)->delete();
            $payment = Payment::where('user_id', $id)->delete();
            $agent = User::where('id', $id)->delete();
        } catch (Exception $e)
        {
            $agent = $e->getMessage();
        }
        return response()->json([
            'agent' => $agent,
        ]);
    }
}
