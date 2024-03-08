<?php

namespace App\Http\Controllers\Api;

use App\Models\Expense;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use DB;

class CommonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function monthlyExpense(Request $request)
    {
        try
        {
            $yr = (string)$request->year;
            $jan = $yr.'-01';
            $feb = $yr.'-02';
            $mar = $yr.'-03';
            $apr = $yr.'-04';
            $my = $yr.'-05';
            $jun = $yr.'-06';
            $jul = $yr.'-07';
            $aug = $yr.'-08';
            $sept = $yr.'-09';
            $oct = $yr.'-10';
            $nov = $yr.'-11';
            $dec = $yr.'-12';

            $january = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $jan)->sum('amount');
            $february = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $feb)->sum('amount');
            $march = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $mar)->sum('amount');
            $april = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $apr)->sum('amount');
            $may = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $my)->sum('amount');
            $june = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $jun)->sum('amount');
            $july = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $jul)->sum('amount');
            $august = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $aug)->sum('amount');
            $september = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $sept)->sum('amount');
            $october = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $oct)->sum('amount');
            $november = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $nov)->sum('amount');
            $december = Expense::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $dec)->sum('amount');            
        } catch (Exception $e)
        {
            $january = $e->getMessage();
        }
        return response()->json([
            'january' => $january,
            'february' => $february,
            'march' => $march,
            'april' => $april,
            'may' => $may,
            'june' => $june,
            'july' => $july,
            'august' => $august,
            'september' => $september,
            'october' => $october,
            'november' => $november,
            'december' => $december
        ]);
    }
}
