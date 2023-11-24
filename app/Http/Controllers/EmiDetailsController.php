<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmiDetailsController extends Controller
{
   public function index(){
    return view('emiDetails');
   }

   public function processEmiData(){
    $tableName = 'emi_details';

    $result = DB::table('loan_details')
        ->selectRaw('MIN(first_payment_date) as min_date, MAX(last_payment_date) as max_date')
        ->first();

    $minDate = new DateTime($result->min_date);
    $maxDate = new DateTime($result->max_date);

    $months = [];
    $currentDate = clone $minDate;

    while ($currentDate <= $maxDate) {
        $months[] = $currentDate->format('Y_M'); 
        $currentDate->modify('+1 month');
    }

    $tableExists = DB::select("SHOW TABLES LIKE '$tableName'");

    if ($tableExists) {
        DB::statement("DROP TABLE $tableName");
    }

    $columns = implode(',', array_map(function ($month) {
        return "`$month` DECIMAL(10,2) DEFAULT 0";
    }, $months));

    $columns = "`id` INT AUTO_INCREMENT PRIMARY KEY, `clientId` INT, $columns";

   

    DB::statement("
        CREATE TABLE $tableName (
            $columns,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    $loanDetails = DB::table('loan_details')->get();
 
    foreach ($loanDetails as $loanDetail) {
        $clientId = $loanDetail->clientid;
        $loanAmount = $loanDetail->loan_amount;
        $numberOfPayments = $loanDetail->num_of_payment;
        $firstPaymentDate = new DateTime($loanDetail->first_payment_date);
        $last_payment_date = new DateTime($loanDetail->last_payment_date);
        $emi = $loanAmount / $numberOfPayments;

        $paymentDuration = $firstPaymentDate->diff($last_payment_date);
        $monthsDifference = $paymentDuration->y * 12 + $paymentDuration->m;
        $monthsDifference=$monthsDifference+1;
        $values = ['clientId' => $clientId];

        $offsetYears = $firstPaymentDate->diff($minDate)->y;
        $offsetMonths = $firstPaymentDate->diff($minDate)->m;
        $offset = $offsetYears * 12 + $offsetMonths;

        $emiSum = 0;
        $calc=0;
        foreach ($months as $index => $month) {
         
        $emiForMonth = $index >= $offset ? $emi : 0;
            if($emiForMonth>0){
               $calc+=1;
               if ($calc > $monthsDifference) {
                break;
            }
            }
     
            $values[$month] = $emiForMonth;

            $emiSum += $emiForMonth;

           
            $relevent=0;
            if ($emiSum >= $loanAmount) {
                for ($i = $index + 1; $i < count($months); $i++) {
                    $values[$months[$i]] = 0;
                }
                break;
            }

            if ($emiForMonth > 0) {
                $lastNonZeroMonth = $month;
            }
        }

       
        if ($emiSum < $loanAmount && $lastNonZeroMonth !== null) {
            $values[$lastNonZeroMonth] += $loanAmount - $emiSum;
        }


        DB::table($tableName)->updateOrInsert(['clientId' => $clientId], $values);
    }

       $processedData = DB::table('emi_details')->get();

       return response()->json($processedData);

    

   
   }
   public function createTable(){
    
   }

  

}
