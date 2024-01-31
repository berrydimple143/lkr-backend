<?php

namespace App\Http\Controllers\Api;

use App\Models\Expense;
use App\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use DB;

class ExpensesController extends Controller
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
                $expenses = DB::table('expenses')
                        ->select(
                            DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d') as transaction_date"), 
                            DB::raw('count(*) as total_transaction'), 
                            DB::raw('sum(amount) as total_expense')
                        )                   
                        ->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d')"))->get();     
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
            $expenses = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d')"), $request->dt)->get();         
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
            $expenses = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d')"), $request->dt)->get();  
            $payments = Payment::where(DB::raw("DATE_FORMAT(date_paid, '%Y-%m-%d')"), $request->dt)->get();        
        } catch (Exception $e)
        {
            $expenses = $e->getMessage();
        }
        return response()->json([
            'expenses' => $expenses,
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
