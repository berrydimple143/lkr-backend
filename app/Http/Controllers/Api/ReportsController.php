<?php

namespace App\Http\Controllers\Api;

use App\Models\Expense;
use App\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use DB;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        try
        {
            DB::beginTransaction();    
                $expenses = DB::table('payments')
                        ->leftJoin('users', 'payments.user_id', '=', 'users.id')
                        ->select(
                            DB::raw("DATE_FORMAT(payments.date_paid, '%Y-%m-%d') as transaction_date"), 
                            DB::raw('count(*) as total_transaction'), 
                            DB::raw('sum(payments.amount) as total_expense')
                        )            
                        ->where('users.role', 'client')       
                        ->groupBy(DB::raw("DATE_FORMAT(payments.date_paid, '%Y-%m-%d')"))->get();     
            DB::commit();
        } catch (Exception $e)
        {
            DB::rollBack();
            $expenses = $e->getMessage();
        }
        return response()->json([
            'expenses' => $expenses,
        ]);
    }

    public function byDate(Request $request)
    {
        try
        {
            $expenses = DB::table('payments')
                        ->leftJoin('users', 'payments.user_id', '=', 'users.id')
                        ->select('payments.amount AS amount', 
                            'users.first_name AS first_name',
                            'users.last_name AS last_name'
                        )  
                        ->where(DB::raw("DATE_FORMAT(payments.date_paid, '%Y-%m-%d')"), $request->dt)
                        ->orderBy('users.last_name')->orderBy('users.first_name')->get();
            //$expenses = Payment::where(DB::raw("DATE_FORMAT(date_paid, '%Y-%m-%d')"), $request->dt)->get();         
        } catch (Exception $e)
        {
            $expenses = $e->getMessage();
        }
        return response()->json([
            'expenses' => $expenses
        ]);
    }

    public function reports(Request $request)
    {
        try
        {
            $expenses = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d')"), $request->dt)->orderBy('transaction_date')->get();  
            $expense = DB::table('payments')
                    ->leftJoin('users', 'payments.user_id', '=', 'users.id')                    
                    ->select('payments.id AS id', 'payments.amount AS amount', 'payments.date_paid AS date_paid', 
                            'payments.method AS method', 'payments.received_by AS received_by', 
                            'users.role AS role', 'users.last_name AS last_name'
                    )->where(DB::raw("DATE_FORMAT(payments.date_paid, '%Y-%m-%d')"), $request->dt)
                    ->where('users.role', 'agent')
                    ->get();
            $payments = DB::table('payments')
                    ->leftJoin('users', 'payments.user_id', '=', 'users.id')
                    ->leftJoin('contacts', 'users.id', '=', 'contacts.user_id')
                    ->leftJoin('areas', 'contacts.area_id', '=', 'areas.id')
                    ->select('payments.id AS payment_id', 'payments.amount AS payment_amount', 'payments.date_paid AS date_paid', 
                            'payments.method AS method', 'users.role AS role', 'users.first_name AS first_name', 
                            'users.last_name AS last_name', 'users.middle_name AS middle_name', 'users.extension_name AS extension_name',
                            'contacts.block AS block', 'contacts.lot AS lot', 'areas.name AS area'               
                    )->where(DB::raw("DATE_FORMAT(payments.date_paid, '%Y-%m-%d')"), $request->dt)
                    ->where('users.role', 'client')
                    ->orderBy('payments.date_paid')->get();       
        } catch (Exception $e)
        {
            $payments = $e->getMessage();
        }
        return response()->json([
            'expenses' => $expenses,
            'expense' => $expense,
            'payments' => $payments,
        ]);
    }

    public function create(Request $request)
    {
        try
        {
            $transaction_date = Carbon::parse($request->transaction_date)->format('Y-m-d H:i:s');
            $data = [
                'description' => $request->description,
                'person_in_charge' => $request->person_in_charge,
                'percentage' => $request->percentage,
                'acknowledge_by' => $request->acknowledge_by,
                'type' => $request->type,
                'amount' => $request->amount,
                'transaction_date' => $transaction_date,
            ];

            $expense = Expense::create($data);            
        } catch (Exception $e)
        {
            $expense = $e->getMessage();
        }
        return response()->json([
            'expense' => $expense,
        ]);
    }

    public function edit(Request $request)
    {
        try
        {
            $expense = Expense::where('id', $request->rid)->first();         
        } catch (Exception $e)
        {
            $expense = $e->getMessage();
        }
        return response()->json([
            'expense' => $expense
        ]);
    }

    public function update(Request $request)
    {
        try
        {            
            $data = [
                'description' => $request->description,
                'person_in_charge' => $request->person_in_charge,
                'percentage' => $request->percentage,
                'acknowledge_by' => $request->acknowledge_by,
                'type' => $request->type,
                'amount' => $request->amount,
            ];
            $expense = Expense::where('id', $request->id)->update($data);
        } catch (Exception $e)
        {
            $expense = $e->getMessage();
        }
        return response()->json([
            'expense' => $expense,
        ]);
    }

    public function destroy(Request $request)
    {
        try
        {            
            $expense = Expense::where('id', $request->id)->delete();
        } catch (Exception $e)
        {
            $expense = $e->getMessage();
        }
        return response()->json([
            'expense' => $expense,
        ]);
    }
}
